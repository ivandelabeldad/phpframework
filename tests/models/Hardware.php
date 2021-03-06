<?php
/**
 * User: Ivan de la Beldad Fernandez
 * Date: 05/12/2016
 * Time: 3:02
 */

namespace FrameworkIvan\Model;


class Hardware extends Model
{

    protected function setTableName(&$tableName)
    {
        $tableName = "hardware";
    }

    protected function table(TableCreator &$fields)
    {
        $fields->int("id")->autoIncrement()->primaryKey();
        $fields->string("name", "100")->unique();
        $fields->decimal("price")->defaultValue(999);
        $fields->string("category")->nullable()->index();
        $fields->date("date_up");
        $fields->int("warranty")->nullable();
        $fields->email("seller_email")->nullable();
        $fields->boolean("confirmed")->defaultValue(false);
    }

}