<?php

$cwd = \getcwd();

if (\is_dir("{$cwd}/src") && (\is_dir("{$cwd}/public") || \is_dir("{$cwd}/web")))
{
    $dirs = [];

    // this is a symfony project, so add possible symfony directories
    foreach (["app", "config", "public", "src", "web"] as $possibleDir)
    {
        if (\is_dir("{$cwd}/{$possibleDir}"))
        {
            $dirs[] = $possibleDir;
        }
    }
}
else
{
    // regular library, so just lint everything
    $dirs = ["src"];
}


$finder = PhpCsFixer\Finder::create()
    ->in($dirs)
    ->exclude(["Migrations", "node_modules", "tests", "var", "vendor", "vendor-bin"])
    ->notPath("bundles.php")
    ->ignoreUnreadableDirs()
;

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        "@PSR1" => true,
        "align_multiline_comment" => [
            "comment_type" => "phpdocs_only",
        ],
        "array_indentation" => true,
        "array_syntax" => [
            "syntax" => "short",
        ],
        "backtick_to_shell_exec" => true,
        "binary_operator_spaces" => true,
        "blank_line_after_namespace" => true,
        "blank_line_after_opening_tag" => false, // we want to allow `<?php declare(strict_types=1);`
        "blank_line_before_statement" => [
            "statements" => [
                "case",
                "default",
                "do",
                "exit",
                "for",
                "foreach",
                "if",
                "switch",
                "try",
                "while",
                "yield",
            ],
        ],
        // disabled, as this forces the brace on the same line, if the method arguments are over multiple lines (to be compatible with PSR-2)
        // see https://github.com/FriendsOfPHP/PHP-CS-Fixer/issues/3637#issuecomment-409987422
//        "braces" => [
//            "allow_single_line_closure" => true,
//            "position_after_anonymous_constructs" => "next",
//            "position_after_control_structures" => "next",
//            "position_after_functions_and_oop_constructs" => "next",
//        ],
        "cast_spaces" => [
            "space" => "single",
        ],
        // Disabled, as we can't configure *2* blank lines to separate
        // see https://github.com/FriendsOfPHP/PHP-CS-Fixer/issues/4001
//        "class_attributes_separation" => [
//            "elements" => ["method", "property"],
//        ],
        "class_definition" => true,
        "combine_consecutive_issets" => true,
        "combine_consecutive_unsets" => true,
        "combine_nested_dirname" => true,
        "compact_nullable_typehint" => true,
        "concat_space" => [
            "spacing" => "one",
        ],
        "constant_case" => [
            "case" => "lower",
        ],
        "declare_equal_normalize" => [
            "space" => "none",
        ],
        "declare_strict_types" => true,
        "dir_constant" => true,
        "doctrine_annotation_array_assignment" => true,
        "elseif" => true,
        "encoding" => true,
        "ereg_to_preg" => true,
        "error_suppression" => true,
        "escape_implicit_backslashes" => [
            "double_quoted" => true,
            "heredoc_syntax" => true,
            "single_quoted" => true,
        ],
        "explicit_indirect_variable" => true,
        "explicit_string_variable" => true,
        "final_internal_class" => true,
        "full_opening_tag" => true,
        "fully_qualified_strict_types" => true,
        "function_to_constant" => true,
        "function_typehint_space" => true,
        "global_namespace_import" => [
            "import_classes" => false,
            "import_constants" => false,
            "import_functions" => false,
        ],
        "heredoc_to_nowdoc" => true,
        "implode_call" => true,
        "include" => true,
        "increment_style" => true,
        "indentation_type" => true,
        "is_null" => true,
        "line_ending" => true,
        "list_syntax" => [
            "syntax" => "short",
        ],
        "logical_operators" => true,
        "lowercase_cast" => true,
        "lowercase_keywords" => true,
        "lowercase_static_reference" => true,
        "magic_constant_casing" => true,
        "magic_method_casing" => true,
        "method_argument_space" => [
            "keep_multiple_spaces_after_comma" => false,
            "on_multiline" => "ensure_fully_multiline",
        ],
        "modernize_types_casting" => true,
        "multiline_comment_opening_closing" => true,
        "multiline_whitespace_before_semicolons" => true,
        "native_constant_invocation" => true,
        "native_function_casing" => true,
        "native_function_invocation" => [
            "include" => ["@all"],
        ],
        "native_function_type_declaration_casing" => true,
        "new_with_braces" => true,
        "no_alias_functions" => [
            "sets" => ["@all"],
        ],
        "no_binary_string" => true,
        "no_blank_lines_after_class_opening" => true,
        "no_blank_lines_after_phpdoc" => true,
        "no_break_comment" => true,
        "no_closing_tag" => true,
        "no_empty_comment" => true,
        "no_empty_statement" => true,
        "no_homoglyph_names" => true,
        "no_leading_import_slash" => true,
        "no_leading_namespace_whitespace" => true,
        "no_mixed_echo_print" => true,
        "no_multiline_whitespace_around_double_arrow" => true,
        "no_null_property_initialization" => true,
        "no_short_bool_cast" => true,
        "no_singleline_whitespace_before_semicolons" => true,
        "no_spaces_after_function_name" => false,
        "no_spaces_around_offset" => true,
        "no_spaces_inside_parenthesis" => true,
        "no_superfluous_elseif" => true,
        "no_superfluous_phpdoc_tags" => [
            "allow_mixed" => true,
        ],
        "no_trailing_comma_in_singleline_array" => true,
        "no_trailing_whitespace" => true,
        "no_trailing_whitespace_in_comment" => true,
        "no_unneeded_control_parentheses" => true,
        "no_unneeded_curly_braces" => true,
        "no_unneeded_final_method" => true,
        "no_unset_cast" => true,
        "no_unset_on_property" => true,
        "no_unused_imports" => true,
        "no_useless_else" => true,
        "no_useless_return" => true,
        "no_whitespace_before_comma_in_array" => true,
        "no_whitespace_in_blank_line" => true,
        "non_printable_character" => [
            "use_escape_sequences_in_strings" => true,
        ],
        "normalize_index_brace" => true,
        "nullable_type_declaration_for_default_null_value" => true,
        "object_operator_without_whitespace" => true,
        "ordered_imports" => true,
        "php_unit_construct" => true,
        "php_unit_dedicate_assert" => true,
        "php_unit_expectation" => true,
        "php_unit_fqcn_annotation" => true,
        "php_unit_internal_class" => false,
        "php_unit_method_casing" => true,
        "php_unit_mock" => true,
        "php_unit_namespaced" => true,
        "phpdoc_order_by_value" => [
            "annotations" => ["covers"],
        ],
        "php_unit_no_expectation_annotation" => true,
        "php_unit_set_up_tear_down_visibility" => true,
        "php_unit_strict" => false,
        "php_unit_test_case_static_method_calls" => [
            "call_type" => "self",
        ],
        "phpdoc_align" => true,
        "phpdoc_indent" => true,
        "phpdoc_no_access" => true,
        "phpdoc_no_alias_tag" => true,
        "phpdoc_no_empty_return" => true,
        "phpdoc_no_package" => true,
        "phpdoc_no_useless_inheritdoc" => true,
        "phpdoc_order" => true,
        "phpdoc_return_self_reference" => true,
        "phpdoc_scalar" => true,
        "phpdoc_separation" => true,
        "phpdoc_single_line_var_spacing" => true,
        "phpdoc_trim" => true,
        "phpdoc_trim_consecutive_blank_line_separation" => true,
        "phpdoc_types" => true,
        "phpdoc_types_order" => [
            "null_adjustment" => "always_last",
            "sort_algorithm" => "none",
        ],
        "phpdoc_var_without_name" => true,
        "pow_to_exponentiation" => true,
        "protected_to_private" => true,
        "psr_autoloading" => true,
        "random_api_migration" => true,
        "return_type_declaration" => [
            "space_before" => "one",
        ],
        "self_accessor" => true,
        "self_static_accessor" => true,
        "semicolon_after_instruction" => true,
        "set_type_to_cast" => true,
        "short_scalar_cast" => true,
        "simple_to_complex_string_variable" => true,
        "single_blank_line_at_eof" => true,
        "single_blank_line_before_namespace" => true,
        "single_class_element_per_statement" => true,
        "single_import_per_statement" => true,
        "single_line_after_imports" => true,
        "single_line_comment_style" => true,
        "single_trait_insert_per_statement" => true,
        "space_after_semicolon" => true,
        "standardize_increment" => true,
        "standardize_not_equals" => true,
        "strict_comparison" => false,
        "strict_param" => true,
        "string_line_ending" => true,
        "switch_case_semicolon_to_colon" => true,
        "switch_case_space" => true,
        "ternary_operator_spaces" => true,
        "ternary_to_null_coalescing" => true,
        "trailing_comma_in_multiline" => true,
        "trim_array_spaces" => true,
        "unary_operator_spaces" => true,
        "visibility_required" => true,
        "void_return" => true,
        "whitespace_after_comma_in_array" => true,
        "yoda_style" => true,
    ])
;
