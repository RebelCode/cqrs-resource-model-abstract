<?php

namespace RebelCode\Storage\Resource\Pdo\Query;

use Dhii\Expression\LogicalExpressionInterface;
use Dhii\Output\Exception\RendererExceptionInterface;
use Dhii\Output\Exception\TemplateRenderExceptionInterface;
use Dhii\Output\TemplateInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;

/**
 * A simple default SQL render functionality trait that simply delegates to a template renderer.
 *
 * @since [*next-version*]
 */
trait RenderSqlConditionCapableTrait
{
    /**
     * Renders an expression as an SQL condition.
     *
     * @since [*next-version*]
     *
     * @param LogicalExpressionInterface $condition    The condition to render.
     * @param string[]|Stringable[]      $columnMap    Optional mapping of field names to column names.
     * @param string[]|Stringable[]      $valueHashMap Optional mapping of term names to their hashes.
     *
     * @return string|Stringable The rendered condition.
     *
     * @throws RendererExceptionInterface If an error occurred while rendering.
     * @throws TemplateRenderExceptionInterface If the renderer failed to render the expression and context.
     */
    protected function _renderSqlCondition(
        LogicalExpressionInterface $condition,
        array $columnMap = [],
        array $valueHashMap = []
    ) {
        $template = $this->_getSqlConditionTemplate($condition);

        if ($template === null) {
            throw $this->_createInvalidArgumentException(
                $this->__('Could not get a template renderer to render given condition'),
                null,
                null,
                $condition
            );
        }

        $context = [$condition, $columnMap, $valueHashMap];

        return $template->render($context);
    }

    /**
     * Retrieves a template renderer instance that can renderer the given condition.
     *
     * @since [*next-version*]
     *
     * @param LogicalExpressionInterface $condition The condition to render.
     *
     * @return TemplateInterface|null The template renderer instance, or null if a template renderer could not be
     *                                resolved for the given condition.
     */
    abstract protected function _getSqlConditionTemplate(LogicalExpressionInterface $condition);

    /**
     * Creates a new Dhii invalid argument exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $message  The error message, if any.
     * @param int|null               $code     The error code, if any.
     * @param RootException|null     $previous The inner exception for chaining, if any.
     * @param mixed|null             $argument The invalid argument, if any.
     *
     * @return InvalidArgumentException The new exception.
     */
    abstract protected function _createInvalidArgumentException(
        $message = null,
        $code = null,
        RootException $previous = null,
        $argument = null
    );

    /**
     * Translates a string, and replaces placeholders.
     *
     * @since [*next-version*]
     * @see   sprintf()
     *
     * @param string $string  The format string to translate.
     * @param array  $args    Placeholder values to replace in the string.
     * @param mixed  $context The context for translation.
     *
     * @return string The translated string.
     */
    abstract protected function __($string, $args = [], $context = null);
}
