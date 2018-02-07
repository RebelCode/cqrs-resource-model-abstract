<?php

namespace RebelCode\Storage\Resource\WordPress\Native;

use Dhii\Util\String\StringableInterface as Stringable;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Traversable;
use WP_Error;

/**
 * Common functionality for objects that can insert posts into the WordPress database.
 *
 * @since [*next-version*]
 */
trait WpInsertCapableTrait
{
    /**
     * Inserts the list of posts into the WordPress database.
     *
     * @since [*next-version*]
     *
     * @param array|ContainerInterface[]|Traversable $posts A list of posts to insert, either as arrays or containers.
     *
     * @throws ContainerExceptionInterface If an error occurred while reading from a container posts.
     * @throws InvalidArgumentException If the argument is not an array, traversable or container.
     */
    protected function _insert($posts)
    {
        foreach ($posts as $_post) {
            $this->_wpInsertPost($this->_normalizeWpPostDataArray($_post));
        }
    }

    /**
     * Retrieves the post data as an array for use in WordPress' insertion function.
     *
     * @since [*next-version*]
     *
     * @param array|ContainerInterface $postData The post data array or container.
     *
     * @return array The prepared post data.
     *
     * @throws ContainerExceptionInterface If an error occurred while reading from the container.
     */
    abstract protected function _normalizeWpPostDataArray($postData);

    /**
     * Wrapper method for the native WordPress post insertion function.
     *
     * @since [*next-version*]
     *
     * @param array $post The post data array, as documented
     *                    {@link https://developer.wordpress.org/reference/functions/wp_insert_post/ here}.
     *
     * @return int|WP_Error The inserted ID on success, a zero of a WP_Error instance on failure.
     */
    abstract protected function _wpInsertPost(array $post);
}