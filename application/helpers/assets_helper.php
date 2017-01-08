<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('icon_url'))
{
	function ico_url($name){
		return base_url().'assets/icons/' . $name. '.png';
	}
}
if ( ! function_exists('css_url'))
{
    function css_url($nom)
    {
        return base_url() . 'assets/css/' . $nom . '.css';
    }
}

if ( ! function_exists('js_url'))
{
    function js_url($nom)
    {
        return base_url() . 'assets/js/' . $nom . '.js';
    }
}

if ( ! function_exists('img_url'))
{
    function img_url($nom)
    {
        return base_url() . 'assets/images/' . $nom;
    }
}

if ( ! function_exists('img'))
{
    function img($nom, $alt = '',$class = '')
    {
        if($class != ''){
            $class = "class='".$class."'";
        }
        return '<img src="' . img_url($nom) . '" alt="' . $alt . '" '.$class.'/>';
    }
}
if ( ! function_exists('ico'))
{
    function ico($nom, $alt = '')
    {
        return '<img src="' . ico_url($nom) . '" alt="' . $alt . '" />';
    }
}
if ( ! function_exists('a_url'))
{
    function a_url($text, $nom = '')
    {
        return '<a href="' . base_url($nom) . '">'. $text .'</a>';
    }
}
?>