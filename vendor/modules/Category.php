<?php
namespace vendor\modules;


class Category
{
    protected $array;
    public function __construct($array)
    {
        $this->sort($array);
    }

    protected function sort($array) {
        $this->array = [];
        foreach ($array as $item) {
            $this->array[$item['parent']][] = $item;
        }
    }

    public function GetIDByParent ($parent = 0) {
        $array = [];
        if (isset($this->array[$parent])) {
            foreach ($this->array[$parent] as $item) {
                $array[] = $item['id'];
                array_splice($array, count($array), 0, $this->GetIDByParent($item['id']));
            }
        }
        return $array;
    }

    public function Show ($parent = 0) {
        $html = "";
        if (isset($this->array[$parent])) {
            $html = '<ul class="'.($parent == 0?'category' : '').'">';
            foreach ($this->array[$parent] as $item) {
                $html .= '<li><a href="/category-'.$item['id'].'">'.$item['title'].'</a>';
                if (isset($this->array[$item['id']])) {
                    $html .= '<span class="show"></span>';
                    $html .= $this->Show($item['id']);
                }
                $html .= "</li>";
            }
            $html .= '</ul>';
        }
        return $html;
    }
}