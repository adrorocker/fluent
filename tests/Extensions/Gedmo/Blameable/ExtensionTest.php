<?php
namespace Tests\Extensions\Gedmo\Blameable;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\DefaultNamingStrategy;
use Gedmo\Blameable\Mapping\Driver\Fluent as Blameable;
use LaravelDoctrine\Fluent\Builders\Field;
use LaravelDoctrine\Fluent\Extensions\ExtensibleClassMetadata;
use LaravelDoctrine\Fluent\Extensions\Gedmo\AbstractTrackingExtension;
use LaravelDoctrine\Fluent\Extensions\Gedmo\Blameable\Extension;
use LaravelDoctrine\Fluent\Relations\ManyToOne;
use PHPUnit_Framework_TestCase;
use Tests\Extensions\Gedmo\TrackingExtensions;

class ExtensionTest extends PHPUnit_Framework_TestCase
{
    use TrackingExtensions;
    
    /**
     * @var Extension
     */
    private $extension;

    protected function setUp()
    {
        $this->classMetadata = new ExtensibleClassMetadata('foo');
        $this->extension     = new Extension($this->classMetadata, $this->fieldName);
    }
    
    public function test_it_should_add_itself_as_a_field_macro()
    {
    	Extension::enable();
        
        $field = Field::make(new ClassMetadataBuilder(new ExtensibleClassMetadata('Foo')), 'string', $this->fieldName);
        
        $this->assertInstanceOf(
            Extension::class, 
            call_user_func([$field, Extension::MACRO_METHOD])
        );
    }
    
    public function test_it_should_add_itself_as_a_many_to_one_macro()
    {
    	Extension::enable();
        
        $manyToOne = new ManyToOne(
            new ClassMetadataBuilder(new ExtensibleClassMetadata('Foo')),
            new DefaultNamingStrategy(),
            $this->fieldName,
            'Bar'
        );
        
        $this->assertInstanceOf(
            Extension::class, 
            call_user_func([$manyToOne, Extension::MACRO_METHOD])
        );
    }
    
    /**
     * @return AbstractTrackingExtension
     */
    protected function getExtension()
    {
        return $this->extension;
    }

    /**
     * @return string
     */
    protected function getExtensionName()
    {
        return Blameable::EXTENSION_NAME;
    }
}
