<?php

/**
 * User: Ivan de la Beldad Fernandez
 * Date: 06/12/2016
 * Time: 8:18
 */

namespace Akimah\Form;
use Akimah\Model;


class Form
{

    public static function open($url, $method = "POST")
    {
        $string = "<form action='$url' method='$method'>";
        return $string;
    }

    public static function close()
    {
        return "</form>";
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
        return new HtmlTag("input", "", $properties);
    }

    public static function date($name, $properties = [])
    {
        if (empty($properties["id"])) $properties["id"] = $name;
        $properties["name"] = $name;
        $properties["type"] = "date";
        return new HtmlTag("input", "", $properties);
    }

    public static function label($for, $content = "")
    {
        return new HtmlTag("label", $content, ["for" => $for]);
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

    public static function model(Model\Model $object, $url, $method = "POST")
    {
        $htmlTags = [];
        array_push($htmlTags, Form::open($url, $method));
        $structure = $object->getStructure();
        array_push($htmlTags, "<fieldset>");
        array_push($htmlTags, "<legend>" . ucfirst($structure->getTableName()) . "</legend>");
        foreach ($structure->getAccessProperties() as $property) {
            array_push($htmlTags, "<div>");
            array_push($htmlTags, Form::label($property->getKey(), ucfirst($property->getKey())));
            array_push($htmlTags, Form::getHtmlTagByProperty($property));
            array_push($htmlTags, "</div>");
        }
        array_push($htmlTags, "<div>");
        array_push($htmlTags, Form::submit());
        array_push($htmlTags, Form::reset());
        array_push($htmlTags, "</div>");
        array_push($htmlTags, "</fieldset>");
        array_push($htmlTags, Form::close());
        $string = "";
        foreach ($htmlTags as $tag) {
            $string .= $tag;
        }
        return $string;
    }

    private static function getHtmlTagByProperty(Model\AccessProperty $property)
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
            default:
                $tag->addProperty("type", "text");
        }
        if(!$property->isNullable()) $tag->addProperty("required");
        return $tag;
    }

}