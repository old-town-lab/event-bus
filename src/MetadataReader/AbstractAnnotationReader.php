<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\MetadataReader;

use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;


/**
 * Class AbstractAnnotationReader
 *
 * @package OldTown\EventBuss\MetadataReader
 */
abstract class  AbstractAnnotationReader implements ReaderInterface
{
    /**
     * Читалка для анотаций
     *
     * @var DoctrineAnnotationReader
     */
    protected $reader;

    /**
     * Набор путей где ищутся классы с метаданными
     *
     * @var array
     */
    protected $paths = [];

    /**
     * Набор путей исключенных из поиска
     *
     * @var array
     */
    protected $excludePaths = [];

    /**
     * Расширение которое должно быть у файла
     *
     * @var string
     */
    protected $fileExtension = '.php';

    /**
     * Кеш для AnnotationDriver#getAllClassNames().
     *
     * @var array|null
     */
    protected $classNames;

    /**
     * Классы в которых описаны используемые анотации
     *
     * @var array
     */
    protected $messageAnnotationClasses = [];

    /**
     * Массив получаемый на основе свойства:$messageAnnotationClasses. Ключами являются и значениями, являются значения из
     * $messageAnnotationClasses
     *
     * @var array|null
     */
    protected $messageAnnotationClassesNormalize;

    /**
     * Кеш результатов разбора натоации
     *
     * @var array
     */
    protected $classAnnotations = [];

    /**
     *
     * @param array|null $paths
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $paths = null)
    {
        $this->reader = new DoctrineAnnotationReader();
        if ($paths) {
            $this->addPaths($paths);
        }
        AnnotationRegistry::registerLoader(function ($class) {
            return (bool) class_exists($class);
        });
    }

    /**
     * Добавляет пути для поиска
     *
     * @param array $paths
     *
     * @return $this
     */
    public function addPaths(array $paths)
    {
        $this->paths = array_unique(array_merge($this->paths, $paths));

        return $this;
    }

    /**
     * Возвращает пути для поиска
     *
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Добавляет пути в исключения
     *
     * @param array $paths
     *
     * @return $this
     */
    public function addExcludePaths(array $paths)
    {
        $this->excludePaths = array_unique(array_merge($this->excludePaths, $paths));

        return $this;
    }

    /**
     * Возвращает пути с исключениями
     *
     * @return array
     */
    public function getExcludePaths()
    {
        return $this->excludePaths;
    }

    /**
     * Возвращает читалку анотаций
     *
     * @return DoctrineAnnotationReader
     */
    public function getReader()
    {
        return $this->reader;
    }

    /**
     * Возвращает расширение, которое должно быть у файлов с метаданными
     *
     * @return string
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * Устанавливает расширение
     *
     * @param string $fileExtension
     *
     * @return $this
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;

        return $this;
    }

    /**
     * @return array
     */
    public function getMessageAnnotationClasses()
    {
        if ($this->messageAnnotationClassesNormalize) {
            return $this->messageAnnotationClassesNormalize;
        }
        $this->messageAnnotationClassesNormalize = array_combine($this->messageAnnotationClasses, $this->messageAnnotationClasses);

        return $this->messageAnnotationClassesNormalize;
    }

    /**
     * Получение анотаций для класса
     *
     * @param $className
     */
    public function getClassAnnotation($className)
    {
        if (array_key_exists($className, $this->classAnnotations)) {
            return $this->classAnnotations[$className];
        }

        $classAnnotation = $this->getReader()->getClassAnnotations(new \ReflectionClass($className));
        $this->classAnnotations[$className] = $classAnnotation;
        return $this->classAnnotations[$className];
    }

    /**
     * Определяет является ли класс конечным в цепочке наследования. Критерий: все метаданные уже загружены
     *

     * @param string $className
     *
     * @return boolean
     */
    public function isTransient($className)
    {
        $classAnnotations = $this->getClassAnnotation($className);

        $messageAnnotationClasses = $this->getMessageAnnotationClasses();
        foreach ($classAnnotations as $annot) {
            if (array_key_exists(get_class($annot), $messageAnnotationClasses)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Возвращает все имена классов, которые могут быта обработанны данным драйвером
     *
     * @return array
     *
     * @throws \OldTown\EventBuss\MetadataReader\Exception\InvalidPathException
     */
    public function getAllClassNames()
    {
        if ($this->classNames !== null) {
            return $this->classNames;
        }

        $classes = [];
        $includedFiles = [];

        foreach ($this->paths as $path) {
            if (! is_dir($path)) {
                $errMsg = sprintf('Некорректный путь: %s', $path);
                throw new Exception\InvalidPathException($errMsg);
            }

            $iterator = new \RegexIterator(
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                ),
                '/^.+' . preg_quote($this->fileExtension) . '$/i',
                \RecursiveRegexIterator::GET_MATCH
            );

            foreach ($iterator as $file) {
                $sourceFile = $file[0];

                if (! preg_match('(^phar:)i', $sourceFile)) {
                    $sourceFile = realpath($sourceFile);
                }

                $current = str_replace('\\', '/', $sourceFile);
                foreach ($this->excludePaths as $excludePath) {
                    $exclude = str_replace('\\', '/', realpath($excludePath));

                    if (strpos($current, $exclude) !== false) {
                        continue 2;
                    }
                }

                /** @noinspection PhpIncludeInspection */
                require_once $sourceFile;

                $includedFiles[$sourceFile] = $sourceFile;
            }
        }

        $declared = get_declared_classes();


        foreach ($declared as $className) {
            $rc = new \ReflectionClass($className);
            $sourceFile = $rc->getFileName();
            if ($sourceFile && array_key_exists($sourceFile, $includedFiles) && ! $this->isTransient($className)) {
                $classes[] = $className;
            }
        }

        $this->classNames = $classes;

        return $classes;
    }
}
