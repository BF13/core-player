<?php
namespace BF13\Component\DomainConnect\Doctrine;

use Doctrine\ORM\EntityRepository;

use BF13\Component\DomainConnect\DomainEntityInterface;

/**
 * Repository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DomainEntityRepository extends EntityRepository
{
    protected $scheme;

    protected $from;

    protected $columns = array();

    protected $conditions = array('_self' => array('items' => array('id' => 'id = :id'),),);

    protected $joins = array();

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);

        if (is_null($this->from)) {

            $alias = substr(substr(strtolower($class->name), strrpos($class->name, '\\') + 1), 0, 1);

            $this->from = array($class->name, $alias);
        }
    }

    public function initializeQuery()
    {
        $query_builder = $this->_em->createQueryBuilder();

        $query_builder->from($this->from[0], $this->from[1]);

        return $query_builder;
    }

    public function joinQuery($query_builder, $join = array())
    {
        if (!sizeOf($this->joins)) {
            return;
        }

        foreach ($this->joins as $name => $alias) {
            $query_builder->leftJoin($name, $alias);
        }
    }

    public function selectQuery($query_builder, $fields = array())
    {
        if (is_null($fields)) {
            $fields = array();
        }

        if (false === $fields) {
            return;
        }

        $selected_fields = array();

        foreach ($fields as $field) {
            if (array_key_exists($field, $this->columns)) {
                $column = $this->columns[$field];

                $selected_fields[] = sprintf('%s as %s', $column['field'], $field);

                if (array_key_exists('joins', $column)) {
                    $this->joins = array_merge($this->joins, $column['joins']);
                }
            } else {

                throw new \Exception(sprintf('Bad ! unexpected field "%s"', $field));
            }
        }

        if (!sizeOf($selected_fields)) {
            $selected_fields[] = $this->from[1];
        }

        $query_builder->select(implode(', ', $selected_fields));
    }

    public function conditionQuery($query_builder, $conditions_values = array())
    {
        if (!$conditions_values) {

            return false;
        }

        if (!is_array($conditions_values)) {

            $conditions_values = array('_self' => array('id' => $conditions_values));
        }

        foreach ($conditions_values as $alias => $value) {

            if (!array_key_exists($alias, $this->conditions)) {

                throw new \Exception(sprintf('Unnkow "%s" condition !', $alias));
            }

            $condition = $this->conditions[$alias];

            $mode = array_key_exists('mode', $condition) ? $condition['mode'] : 'basic';

            switch($mode) {

                case 'basic':

                    if (array_key_exists('items', $condition)) {

                        foreach ($condition['items'] as $param_name => $condition_part) {

                            if(is_array($condition_part)) {

                                $condition_part = $condition_part['pattern'];
                            }

                            if (is_array($value) && array_key_exists($param_name, $value) || is_string($value)) {

                                if (is_array($value)) {

                                    $param_value = $value[$param_name];

                                    if(is_array($param_value) && 0 == sizeOf($param_value)){
                                        continue;
                                    }

                                    $query_builder->andWhere($condition_part)->setParameter($param_name, $param_value);

                                } else {

                                    $param_value = $value;

                                    $query_builder->andWhere($condition_part)->setParameter($param_name, $param_value);
                                }
                            }
                        }
                    }

                    if (array_key_exists('joins', $condition)) {

                        $this->joins = array_merge($this->joins, $condition['joins']);
                    }

                    break;
                case 'function':

                    $function = sprintf('%sCondition', $alias);

                    if(! method_exists($this, $function))
                    {
                        throw new \Exception(sprintf('Vous devez implémenter la méthode "%s" !', $function));
                    }

                    $this->$function($query_builder, $conditions_values[$alias]);

                    break;
                default:
                    throw new \Exception(sprintf('Mode "%s" inconnu !', $mode));
            }
        }
    }

    public function orderBy($query_builder, $data)
    {
        $allowed_dir = array('ASC', 'DESC');

        foreach ($data as $order_field => $dir) {
            if (!array_key_exists($order_field, $this->columns)) {

                throw new \Exception(sprintf('Unknow "%s" field !', $order_field));
            }

            if (!in_array($dir, $allowed_dir)) {

                throw new \Exception(sprintf('Bad "%s" direction !', $dir));
            }

            $column = $this->columns[$order_field];

            if (array_key_exists('joins', $column)) {
                $this->joins = array_merge($this->joins, $column['joins']);
            }

            $query_builder->addOrderBy($column['field'], $dir);
        }
    }

    public function pager($query_builder, $data)
    {
        $query_builder->setMaxResults($data['max_result']);

        $query_builder->setFirstResult($data['offset']);
    }

    public function total($query_builder)
    {
        $query_builder->resetDQLPart('select');

        $query_builder->select('COUNT('.$this->from[1].')');

        $query_builder->setFirstResult(0);

        return $query_builder->getQuery()->getSingleScalarResult();
    }

    public function initDomainScheme($scheme)
    {
        die('domain entity repository `namespace`: ' . __NAMESPACE__);
        
        $this->from = array(__NAMESPACE__, $scheme['alias']);

        if(is_array($this->columns)) {

            $this->columns = array_merge($scheme['properties'], $this->columns);

        } else {

            $this->columns = $scheme['properties'];
        }

        if(is_array($this->conditions)) {

            $this->conditions = array_merge($scheme['conditions'], $this->conditions);

        } else {

            $this->conditions = $scheme['conditions'];
        }
    }
}