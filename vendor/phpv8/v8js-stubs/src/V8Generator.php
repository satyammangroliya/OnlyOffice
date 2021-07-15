<?php
namespace V8JsStubs;

/**
 * JavaScript generator function companion (for generators passed from JavaScript to PHP).
 */
final class V8Generator implements Iterator
{
    /**
     * V8Generator must not be constructed directly.
     *
     * @throws V8JsException
     */
    public function __construct()
    {}

    /**
     * {@inheritdoc}
     */
    public function current()
    { }

    /**
     * {@inheritdoc}
     */
    public function next()
    { }

    /**
     * Return the key of the current element
     * @return false
     */
    public function key()
    { }

    /**
     * {@inheritdoc}
     */
    public function valid()
    { }

    /**
     * JavaScript generators cannot be rewound.
     *
     * This methods throws an exception if called after generator has been started.
     *
     * @throws V8JsException
     * @return false
     */
    public function rewind()
    { }
}
