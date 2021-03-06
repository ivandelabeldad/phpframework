<?php

/**
 * User: Ivan de la Beldad Fernandez
 * Date: 06/12/2016
 * Time: 8:18
 */

namespace FrameworkIvan\Form;

use FrameworkIvan\Model;


class Form
{

    public static function open($url, $method = "POST", $allowFiles = false)
    {
        if ($allowFiles) {
            $encode = "multipart/form-data";
        } else {
            $encode = "application/x-www-form-urlencoded";
        }
        $string = "<form action='$url' method='$method' enctype='$encode'>";
        $string .= "<fieldset>";
        return $string;
    }

    public static function close()
    {
        $string = "</fieldset>";
        $string .= "</form>";
        return $string;
    }

    public static function legend($title)
    {
        $string = "<legend>$title</legend>";
        return $string;
    }

    public static function text($name, $placeholder = "", $properties = [])
    {
        if ($placeholder !== "") $properties["placeholder"] = $placeholder;
        if (empty($properties["id"])) $properties["id"] = $name;
        $properties["name"] = $name;
        $properties["type"] = "text";
        return new HtmlTag("input", "", $properties);
    }

    public static function number($name, $placeholder = "", $properties = [])
    {
        if ($placeholder !== "") $properties["placeholder"] = $placeholder;
        if (empty($properties["id"])) $properties["id"] = $name;
        $properties["name"] = $name;
        $properties["type"] = "number";
        $properties["step"] = "any";
        return new HtmlTag("input", "", $properties);
    }

    public static function date($name, $properties = [])
    {
        $properties["type"] = "date";
        if (empty($properties["id"])) $properties["id"] = $name;
        $properties["name"] = $name;
        return new HtmlTag("input", "", $properties);
    }

    public static function label($for, $content = "")
    {
        return new HtmlTag("label", $content, ["for" => $for]);
    }

    public static function checkbox($name, $properties = [])
    {
        $properties["type"] = "checkbox";
        if (empty($properties["id"])) $properties["id"] = $name;
        $properties["name"] = $name;
        return new HtmlTag("input", "", $properties);
    }

    public static function radio($name, $value, $properties = [])
    {
        $properties["type"] = "radio";
        if (empty($properties["id"])) $properties["id"] = $name . "_" . $value;
        $properties["name"] = $name;
        $properties["value"] = $value;
        return new HtmlTag("input", "", $properties);
    }

    public static function file($name, $properties = [])
    {
        if (empty($properties["id"])) $properties["id"] = $name;
        $properties["name"] = $name;
        $properties["type"] = "file";
        return new HtmlTag("input", "", $properties);
    }

    public static function hidden($name, $value, $properties = [])
    {
        if (empty($properties["id"])) $properties["id"] = $name;
        $properties["name"] = $name;
        $properties["type"] = "hidden";
        $properties["value"] = $value;
        return new HtmlTag("input", "", $properties);
    }

    public static function submit($value = "Submit", $properties = [])
    {
        $properties["value"] = $value;
        $properties["type"] = "submit";
        return new HtmlTag("input", "", $properties);
    }

    public static function reset($value = "Reset", $properties = [])
    {
        $properties["value"] = $value;
        $properties["type"] = "reset";
        return new HtmlTag("input", "", $properties);
    }

    public static function selectOpen($name, $properties = [])
    {
        if (empty($properties["id"])) $properties["id"] = $name;
        $properties["name"] = $name;
        $tag = new HtmlTag("select", "", $properties);
        return $tag->toStringOpenTag();
    }

    public static function selectClose()
    {
        $tag = new HtmlTag("select", "");
        return $tag->toStringCloseTag();
    }

    public static function option($value, $content, $properties = [])
    {
        $properties["value"] = $value;
        return new HtmlTag("option", $content , $properties);
    }

    public static function model(Model\Model $object, $url, $method = "POST")
    {
        $htmlTags = [];
        array_push($htmlTags, Form::open($url, $method, true));
        $structure = $object->getTable();
        array_push($htmlTags, "<legend>" . ucfirst($structure->getTableName()) . "</legend>");
        foreach ($structure->getProperties() as $property) {
            if ($property->isAutoIncrement()) continue;
            array_push($htmlTags, "<div>");
            array_push($htmlTags, Form::label($property->getKey(), ucfirst($property->getKey())));
            array_push($htmlTags, Form::getHtmlTagByProperty($property));
            array_push($htmlTags, "</div>");
        }
        array_push($htmlTags, "<div>");
        array_push($htmlTags, Form::submit());
        array_push($htmlTags, Form::reset());
        array_push($htmlTags, "</div>");
        array_push($htmlTags, Form::close());
        $string = "";
        foreach ($htmlTags as $tag) {
            $string .= $tag;
        }
        return $string;
    }

    private static function getHtmlTagByProperty(Model\Property $property)
    {
        $tag = new HtmlTag("input");
        $tag->addProperty("id", $property->getKey());
        $tag->addProperty("name", $property->getKey());
        switch ($property->getType()) {
            case Model\Property::FIELD_STRING:
                $tag->addProperty("type", "text");
                break;
            case Model\Property::FIELD_INT:
                $tag->addProperty("type", "number");
                break;
            case Model\Property::FIELD_DECIMAL:
                $tag->addProperty("type", "number");
                $tag->addProperty("step", "any");
                break;
            case Model\Property::FIELD_DATE:
                $tag->addProperty("type", "date");
                break;
            case Model\Property::FIELD_TIME:
                $tag->addProperty("type", "text");
                break;
            case Model\Property::FIELD_DATETIME:
                $tag->addProperty("type", "text");
                break;
            case Model\Property::FIELD_EMAIL:
                $tag->addProperty("type", "email");
                break;
            case Model\Property::FIELD_BOOLEAN:
                $tag->addProperty("type", "checkbox");
                $tag->addProperty("value", 1);
                break;
            case Model\Property::FIELD_IMAGE:
                $tag->addProperty("type", "file");
                break;
            default:
                $tag->addProperty("type", "text");
        }
        if (!$property->isNullable()) $tag->addProperty("required");
        return $tag;
    }

}
