<?php

namespace Framework;

class View
{
    /**
     * Render a PHP view template with data.
     *
     * @param  string  $template  Path relative to the /views directory (e.g. 'home').
     * @param  array<string, mixed>  $data  Associative array of data to extract into the template.
     * @param  string  $layout  Name of the layout file to render the template inside of.
     * @return string Rendered HTML content.
     */
    public static function render(string $template, array $data = [], string $layout = 'root'): string
    {
        $viewPath = __DIR__ . "/../templates/$template.php";

        if (! file_exists($viewPath)) {
            throw new \RuntimeException("View not found: $viewPath");
        }

        extract($data);

        ob_start();
        require $viewPath;
        $page = ob_get_clean();
        if (!$page) {
            throw new \RuntimeException('Unable to render template output buffer to html response');
        }

        if ($layout) {
            $layoutPath = __DIR__ . "/../templates/$layout.php";
            if (!file_exists($layoutPath)) {
                throw new \RuntimeException("Layout not found: $layoutPath");
            }
            ob_start();
            require $layoutPath;

            $buffer = ob_get_clean();
            if ($buffer) {
                throw new \RuntimeException('Unable to render layout output buffer into html response');
            }
        }

        return $page;
    }
}
