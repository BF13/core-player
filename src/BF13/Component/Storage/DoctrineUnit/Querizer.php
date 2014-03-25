<?php
namespace BF13\Component\Storage\DoctrineUnit;

use BF13\Component\Storage\StorageQuerizerInterface;
use BF13\Component\Storage\StorageRepositoryInterface;
use BF13\Component\Storage\Exception\StorageException;
use Doctrine\ORM\QueryBuilder;

/**
 * @author FYAMANI
 *
 */
class Querizer implements StorageQuerizerInterface
{
    protected $builder;

    protected $definition;

    public function __construct(StorageRepositoryInterface $repository, QueryBuilder $builder)
    {
        $this->definition = $repository->getDefaultSchema();

        $this->builder = $builder;
    }

    /**
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageQuerizerInterface::datafields()
     */
    public function datafields($fields = array())
    {
        if (is_null($fields)) {

            $fields = array();
        }

        if (false === $fields) {

            return;
        }

        $selected_fields = array();

        foreach ($fields as $field) {

            if (array_key_exists($field, $this->definition['columns'])) {

                $column = $this->definition['columns'][$field];

                $selected_fields[] = sprintf('%s as %s', $column['field'], $field);

                if (array_key_exists('joins', $column)) {

                    $this->definition['joins'] = array_merge($this->definition['joins'], $column['joins']);
                }

            } else {

                throw new StorageException(sprintf('Unexpected field "%s" !', $field));
            }
        }

        if (!sizeOf($selected_fields)) {

            $selected_fields[] = $this->definition['from'][1];
        }

        $this->builder->select(implode(', ', $selected_fields));

        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageQuerizerInterface::conditions()
     */
    public function conditions($conditions = array())
    {
        if (!$conditions) {

            return;
        }

        if (!is_array($conditions)) {

            $conditions = array('_self' => array('id' => $conditions));
        }

        foreach ($conditions as $alias => $value) {

            if (!array_key_exists($alias, $this->definition['conditions'])) {

                throw new StorageException(sprintf('Unnkow "%s" condition !', $alias));
            }

            $condition = $this->definition['conditions'][$alias];

            $mode = array_key_exists('mode', $condition) ? $condition['mode'] : 'basic';

            switch ($mode) {

            case 'basic':
                if (array_key_exists('items', $condition)) {

                    foreach ($condition['items'] as $param_name => $condition_part) {

                        if (is_array($condition_part)) {

                            $condition_part = $condition_part['pattern'];
                        }

                        if (is_array($value) && array_key_exists($param_name, $value) || is_string($value)) {

                            if (is_array($value)) {

                                $param_value = $value[$param_name];

                                if (is_array($param_value) && 0 == sizeOf($param_value)) {

                                    continue;
                                }

                                $this->builder->andWhere($condition_part)->setParameter($param_name, $param_value);

                            } else {

                                $param_value = $value;

                                $this->builder->andWhere($condition_part)->setParameter($param_name, $param_value);
                            }
                        }
                    }
                }

                if (array_key_exists('joins', $condition)) {

                    $this->definition['joins'] = array_merge($this->definition['joins'], $condition['joins']);
                }

                break;

            case 'function':
                $function = sprintf('%sCondition', $alias);

                if (!method_exists($this, $function)) {
                    throw new StorageException(sprintf('Vous devez implémenter la méthode "%s" !', $function));
                }

                $this->$function($this->builder, $conditions[$alias]);

                break;

            default:
                throw new StorageException(sprintf('Mode "%s" inconnu !', $mode));
            }
        }

        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageQuerizerInterface::groupBy()
     */
    public function group($groups = '')
    {
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageQuerizerInterface::sort()
     */
    public function sort($sort = array())
    {
        if (is_null($sort)) {

            return;
        }

        $allowed_dir = array('ASC', 'DESC');

        foreach ($sort as $order_field => $dir) {

            if (!array_key_exists($order_field, $this->definition['columns'])) {

                throw new StorageException(sprintf('Unknow "%s" field !', $order_field));
            }

            if (!in_array($dir, $allowed_dir)) {

                throw new StorageException(sprintf('Bad "%s" direction !', $dir));
            }

            $column = $this->definition['columns'][$order_field];

            if (array_key_exists('joins', $column)) {

                $this->definition['joins'] = array_merge($this->definition['joins'], $column['joins']);
            }

            $this->builder->addOrderBy($column['field'], $dir);
        }

        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageQuerizerInterface::result()
     */
    public function result($mode = null)
    {
        return current($this->results($mode));
    }

    /**
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageQuerizerInterface::results()
     */
    public function results($mode = null)
    {
        return $this->exec();
    }

    /**
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageQuerizerInterface::pager()
     */
    public function pager($offset = 0, $max_result = 5)
    {
    }

    /**
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageQuerizerInterface::resultsWithPager()
     */
    public function resultsWithPager($offset = 0, $max_result = 5)
    {
    }

    protected function exec($mode = null)
    {
        $this->makeJoinQuery();

        $result = $this->builder->getQuery()->getResult($mode);

        return $result;
    }

    public function getQueryBuilder()
    {
        $this->makeJoinQuery();

        return $this->builder;
    }

    public function setSchema(Schema $schema)
    {
        $this->definition['from'] = array($schema->getName(), $schema->getAlias());

        $this->builder->from($this->definition['from'][0], $this->definition['from'][1]);

        if (is_array($this->definition['columns'])) {

            $this->definition['columns'] = array_merge($schema->getProperties(), $this->definition['columns']);

        } else {

            $this->definition['columns'] = $schema->getProperties();
        }

        if (is_array($this->definition['conditions'])) {

            $this->definition['conditions'] = array_merge($schema->getConditions(), $this->definition['conditions']);

        } else {

            $this->definition['conditions'] = $schema->getConditions();
        }

        return $this;
    }

    protected function makeJoinQuery()
    {
        if (!sizeOf($this->definition['joins'])) {

            return;
        }

        foreach ($this->definition['joins'] as $name => $alias) {

            $this->builder->leftJoin($name, $alias);
        }
    }
}
