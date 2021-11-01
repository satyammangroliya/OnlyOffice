<?php

namespace srag\Plugins\OnlyOffice\StorageService\FileSystem;

use ILIAS\DI\Container;
use ILIAS\Filesystem\Exception\IOException;
use ILIAS\FileUpload\DTO\UploadResult;
use ILIAS\FileUpload\Location;
use srag\Plugins\OnlyOffice\StorageService\DTO\FileVersion;
use ILIAS\Filesystem\Stream\Streams;
use srag\Plugins\OnlyOffice\StorageService\DTO\FileChange;

/**
 * Class FileSystemService
 * @package srag\Plugins\OnlyOffice\StorageService\FileSystem
 * @author  Theodor Truffer <theo@fluxlabs.ch>
 *          Sophie Pfister <sophie@fluxlabs.ch>
 */
class FileSystemService
{

    const BASE_PATH = '/only_office/';
    /**
     * @var Container
     */
    protected $dic;

    /**
     * FileRepository constructor.
     * @param Container $dic
     */
    public function __construct(Container $dic)
    {
        $this->dic = $dic;
    }

    /**
     * @param UploadResult $upload_result
     * @param int          $obj_id
     * @param string       $file_id
     * @throws IOException
     */
    public function storeUploadResult(
        UploadResult $upload_result,
        int $obj_id,
        string $file_id,
        string $file_name = FileVersion::FIRST_VERSION
    ) : string {
        $ext = pathinfo($upload_result->getName(), PATHINFO_EXTENSION);
        $file_name .= '.' . $ext;

        $path = $this->createAndGetPath($obj_id, $file_id);
        $this->dic->upload()->moveOneFileTo(
            $upload_result,
            $path,
            Location::WEB,
            $file_name
        );
        $path .= $file_name;
        return $path;
    }

    public function copyTemplateAs(
        string $template_path,
        int $file_id,
        string $uuid,
        string $title,
        string $extension,
        string $file_name = FileVersion::FIRST_VERSION
    ) : string {
        $child_path = $this->createAndGetPath($file_id, $uuid) . $file_name . DIRECTORY_SEPARATOR . $title . "." . $extension;
        $this->dic->filesystem()->web()->copy($template_path, $child_path);
        return $child_path;
    }

    public function storeNewVersionFromString(
        string $content,
        int $obj_id,
        string $uuid,
        int $version,
        string $extension
    ) : string {
        $path = $this->createAndGetPath($obj_id, $uuid) . $version . '.' . $extension;
        $this->dic->logger()->root()->info('Storing as: ' . $path);
        $stream = Streams::ofString($content);
        $web = $this->dic->filesystem()->web();
        $web->writeStream($path, $stream);
        return $path;
    }

    /**
     * Store a template from the config form
     * @param string $tmp_path  the path where the file is currently stored
     * @param string $type      word, excel or powerpoint
     * @param string $extension file extension
     * @throws IOException
     */
    public function storeTemplate(string $tmp_path, string $type, string $extension) : string
    {
        // Define path and create it if it does not exist
        $path = self::BASE_PATH . "templates/" . $type . "/";
        if (!$this->dic->filesystem()->web()->hasDir($path)) {
            $this->dic->filesystem()->web()->createDir($path);
        }
        $file_name = $type . "." . $extension;
        $path .= $file_name;

        // delete the old template file if it already exists
        if ($this->dic->filesystem()->web()->has($path)) {
            $this->dic->filesystem()->web()->delete($path);
        }
        // store the new template file
        $stream = Streams::ofString($tmp_path);
        $this->dic->filesystem()->web()->writeStream($path, $stream);
        return $path;

    }

    public function storeChanges(string $content, int $obj_id, string $uuid, int $version, string $extension) : string
    {
        $path = $this->createAndGetPath($obj_id, $uuid, true) . $version . '.' . $extension;
        $stream = Streams::ofString($content);
        $web = $this->dic->filesystem()->web();
        $web->writeStream($path, $stream);
        return $path;
    }

    public function storeVersionCopy(FileVersion $parent_version, string $uuid, int $file_id) : string
    {
        $parent_path = $parent_version->getUrl();
        $extension = pathinfo($parent_path, PATHINFO_EXTENSION);
        $child_path = $this->createAndGetPath($file_id, $uuid,
                false) . $parent_version->getVersion() . '.' . $extension;;
        $web = $this->dic->filesystem()->web();
        $web->copy($parent_path, $child_path);
        return $child_path;
    }

    public function storeChangeCopy(FileChange $parent_change, string $uuid, int $file_id) : string
    {
        $parent_path = $parent_change->getChangesUrl();
        $extension = pathinfo($parent_path, PATHINFO_EXTENSION);
        $child_path = $this->createAndGetPath($file_id, $uuid, true) . $parent_change->getVersion() . '.' . $extension;;
        $web = $this->dic->filesystem()->web();
        $web->copy($parent_path, $child_path);
        return $child_path;
    }

    public function deletePath(int $file_id)
    {
        $path = self::BASE_PATH . $file_id;
        $web = $this->dic->filesystem()->web();
        $web->deleteDir($path);

    }

    /**
     * @param int    $obj_id
     * @param string $file_id
     * @return string
     * @throws IOException
     */
    protected function createAndGetPath(int $file_id, string $uuid, bool $isChange = false) : string
    {
        if (!$isChange) {
            $path = self::BASE_PATH . $file_id . DIRECTORY_SEPARATOR . $uuid . DIRECTORY_SEPARATOR;
        } else {
            $path = self::BASE_PATH . $file_id . DIRECTORY_SEPARATOR . $uuid . DIRECTORY_SEPARATOR . 'changes' . DIRECTORY_SEPARATOR;
        }

        if (!$this->dic->filesystem()->web()->hasDir($path)) {
            $this->dic->filesystem()->web()->createDir($path);
        }

        return $path;
    }
}