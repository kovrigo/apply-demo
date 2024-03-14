<?php

namespace App\Meta;

class Manager
{

    public static function makeResource($meta)
    {
        (new ModelGenerator($meta))->generateModel();
        (new PolicyGenerator($meta))->generatePolicy();
        (new ResourceGenerator($meta))->generateResource();
		(new MigrationGenerator($meta))->generateResourceMigration();
    }

    public static function deleteResource($meta)
    {
        (new ModelGenerator($meta))->deleteModel();
        (new PolicyGenerator($meta))->deletePolicy();
    	(new ResourceGenerator($meta))->deleteResource();
    	(new MigrationGenerator($meta))->generateResourceMigration(true);
    }

    public static function addField($meta)
    {
        (new MigrationGenerator($meta))->generateFieldMigration();
    }

    public static function deleteField($meta)
    {
        (new MigrationGenerator($meta))->generateFieldMigration(true);
    }

    public static function addRelation($meta)
    {
        (new ModelGenerator($meta))->generateRelation();
        (new PolicyGenerator($meta))->generateRelation();
        (new MigrationGenerator($meta))->generateRelationMigration();
    }

    public static function deleteRelation($meta)
    {
        (new ModelGenerator($meta))->deleteRelation();
        (new PolicyGenerator($meta))->deleteRelation();
        (new MigrationGenerator($meta))->generateRelationMigration(true);
    }

    public static function getFieldClasses()
    {
        return ['Trix', 'Text', 'Textarea', 'Int', 'Float', 'Boolean', 'DateTime', 'Date', 'Json', 'Code', 'NovaTrumbowyg'];
    }

    public static function getRelationTypes()
    {
        return ['OneToMany', 'ManyToMany', 'OneToManyPolymorphic'];
    }

}

