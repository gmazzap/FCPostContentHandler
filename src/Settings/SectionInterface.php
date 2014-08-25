<?php namespace GM\FCPCH\Settings;

interface SectionInterface {

    public function getId();

    public function title();

    public function subTitle();

    public function addField( Fields\FieldInterface $field );

    public function getFields();

    public function getField( $id );
}