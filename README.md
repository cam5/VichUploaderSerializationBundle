# VichUploaderSerializationBundle

Provides integration between [VichUploaderBundle](https://github.com/dustin10/VichUploaderBundle "VichUploaderBundle") and
[JMSSerializerBundle](https://github.com/dustin10/VichUploaderBundle "JMSSerializerBundle").
Allows to generate full or related URI to the file during the serialization.

[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/fre5h/VichUploaderSerializationBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/fre5h/VichUploaderSerializationBundle/)
[![Build Status](https://img.shields.io/travis/fre5h/VichUploaderSerializationBundle.svg?style=flat-square)](https://travis-ci.org/fre5h/VichUploaderSerializationBundle)
[![CodeCov](https://img.shields.io/codecov/c/github/fre5h/VichUploaderSerializationBundle.svg?style=flat-square)](https://codecov.io/github/fre5h/VichUploaderSerializationBundle)
[![License](https://img.shields.io/packagist/l/fresh/vich-uploader-serialization-bundle.svg?style=flat-square)](https://packagist.org/packages/fresh/vich-uploader-serialization-bundle)
[![Latest Stable Version](https://img.shields.io/packagist/v/fresh/vich-uploader-serialization-bundle.svg?style=flat-square)](https://packagist.org/packages/fresh/vich-uploader-serialization-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/fresh/vich-uploader-serialization-bundle.svg?style=flat-square)](https://packagist.org/packages/fresh/vich-uploader-serialization-bundle)
[![Dependency Status](https://img.shields.io/versioneye/d/php/fresh:vich-uploader-serialization-bundle.svg?style=flat-square)](https://www.versioneye.com/user/projects/565a0f4b036c32003d000008)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/a40e1ac6-3b2b-4405-b7c5-53d020a5cf93.svg?style=flat-square)](https://insight.sensiolabs.com/projects/a40e1ac6-3b2b-4405-b7c5-53d020a5cf93)
[![Gitter](https://img.shields.io/badge/gitter-join%20chat-brightgreen.svg?style=flat-square)](https://gitter.im/fre5h/VichUploaderSerializationBundle?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![knpbundles.com](http://knpbundles.com/fre5h/VichUploaderSerializationBundle/badge-short)](http://knpbundles.com/fre5h/VichUploaderSerializationBundle)

## Installation

```php composer.phar require fresh/vich-uploader-serialization-bundle='dev-master'```

### Register the bundle

To start using the bundle, register it in `app/AppKernel.php`:

```php
public function registerBundles()
{
    $bundles = [
        // Other bundles...
        new Fresh\VichUploaderSerializationBundle\FreshVichUploaderSerializationBundle(),
    ];
}
```

## Using

Bundle provides two annotations which allow the serialization of `@Vich\UploadableField` fields in your entities.
At first you have to add `@VichSerializableClass` to the entity class which has uploadable fields.
Then you have to add `@VichSerializableField` annotation to the uploadable field you want to serialize.

Annotation `@VichSerializableClass` does not have any option.  
Annotation `@VichSerializableField` has one required option `value` (or `field`) which value should link to the field with `@UploadableField` annotation.
It can be set like this `@VichSerializableField("photoFile")` or `@VichSerializableField(field="photoFile")`.
Also there is another option `includeHost`, it is not required and by default is set to `true`.
But if you need you can exclude the host from generated URI `@VichSerializableField("photoFile", includeHost=false)`.

The generated URI by default:

```json
{
  "photo": "http://example.com/uploads/users/photos/5659828fa80a7.jpg",
  "cover": "http://example.com/uploads/users/cover/456428fa8g4a8.jpg",
}
```

The generated URI with `includeHost` set to `false`:

```json
{
  "photo": "/uploads/users/photos/5659828fa80a7.jpg",
  "cover": "/uploads/users/cover/456428fa8g4a8.jpg",
}
```

### Example of entity with serialized uploadable fields

```php
<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fresh\VichUploaderSerializationBundle\Annotation as Fresh;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * User Entity
 *
 * @ORM\Table(name="users")
 * @ORM\Entity()
 *
 * @Vich\Uploadable
 * @Fresh\VichSerializableClass
 */
class User
{
    /**
     * @var string $photoName Photo name
     *
     * @ORM\Column(type="string", length=255)
     *
     * @JMS\Expose
     * @JMS\SerializedName("photo")
     *
     * @Fresh\VichSerializableField("photoFile")
     */
    private $photoName;

    /**
     * @var File $photoFile Photo file
     *
     * @JMS\Exclude
     *
     * @Vich\UploadableField(mapping="user_photo_mapping", fileNameProperty="photoName")
     */
    private $photoFile;
    
    /**
     * @var string $coverName Cover name
     *
     * @ORM\Column(type="string", length=255)
     *
     * @JMS\Expose
     * @JMS\SerializedName("cover")
     *
     * @Fresh\VichSerializableField("coverFile", includeHost=false)
     */
    private $coverName;

    /**
     * @var File $coverFile Cover file
     *
     * @JMS\Exclude
     *
     * @Vich\UploadableField(mapping="user_cover_mapping", fileNameProperty="coverName")
     */
    private $coverFile;    
}
```