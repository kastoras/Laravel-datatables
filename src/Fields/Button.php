<?php

namespace SingleQuote\DataTables\Fields;

use SingleQuote\DataTables\Controllers\Field;
use Illuminate\Support\Str;

/**
 * Description of Label
 *
 * @author Wim Pruiksma <wim.pruiksma@nugtr.nl>
 */
class Button extends Field
{
    /**
     * The date view
     *
     * @var string
     */
    protected $view = "button";

    /**
     * Unique button id
     *
     * @var string
     */
    public $id;

    /**
     * Set the icon
     *
     * @var string
     */
    public $icon;

    /**
     * THe route url for when the user clicks the button
     *
     * @var string
     */
    public $route;

    /**
     * Keys that need to be replaced in the route
     *
     * @var array
     */
    public $routeReplace;

    /**
     * The route method
     *
     * @var array
     */
    public $method = "GET";

    /**
     * The route target
     *
     * @var array
     */
    public $target;

    /**
     * Set the button label
     *
     * @var string
     */
    public $label;

    /**
     * Set onclick method
     *
     * @var string
     */
    public $onClick;

    /**
     * Init the fields class
     *
     * @param string $column
     * @return $this
     */
    public static function make(string $column)
    {
        $class         = new Button;
        $class->column = $column;
        $class->id     = uniqid('button_');

        return $class;
    }

    /**
     * Set the icon for the button
     *
     * @param string $class
     * @param string $name
     * @return $this
     */
    public function icon(string $class, string $name = "")
    {
        $this->icon = "<i class=\"$class\">$name</i>";

        return $this;
    }

    /**
     * Set the route to redirect the user to when the button is clicked
     *
     * @param string $route
     * @param mixed $parameters
     * @return $this
     */
    public function route(string $route, $parameters)
    {
        $params = is_array($parameters) ? $parameters : [$parameters];

        foreach($params as $index => $param){

            $this->routeReplace["Q{$param}Q"] = $param;

            $params[$index] = "Q{$param}Q";
        }

        $this->route = route($route, $params);

        return $this;
    }

    /**
     * Set the route method
     *
     * @param string $method
     * @return $this
     */
    public function method(string $method)
    {
        $this->method = strtoupper($method);

        return $this;
    }

    /**
     * Set the route target
     *
     * @param string $target
     * @return $this
     */
    public function target(string $target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Set the button label
     *
     * @param string $label
     * @return $this
     */
    public function label(string $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set the onclick method
     *
     * @param string $onClick
     * @return $this
     */
    public function onclick(string $onClick)
    {
        $this->onClick = $onClick;

        return $this;
    }
}
