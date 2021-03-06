<?php
/**
 * User: Ivan de la Beldad Fernandez
 * Date: 07/12/2016
 * Time: 2:53
 */

namespace FrameworkIvan\Model;


class Order extends Model
{

    protected function setTableName(&$tableName)
    {
        $tableName = "orders";
    }

    protected function table(TableCreator &$fields)
    {
        $fields->int("id")->autoIncrement()->primaryKey();
        $fields->int("product_id")->foreignKey("product_id_fk")->references("hardware")->on("id");
        $fields->int("quantity")->defaultValue(1);
        $fields->datetime("datetime_order");
        $fields->string("customer_name");
    }

}