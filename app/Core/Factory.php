<?php

declare(strict_types=1);

namespace App\Core;

use Nette\Application\UI\Form;
use Nette\Forms\Controls\TextInput;
use Nette\Localization\Translator;
use Nette\Security\User;

/**
 * Factory class to create forms with optional protection based on user login status.
 *
 * This class provides methods to create different types of form fields such as text, password, and email inputs,
 * with support for validation rules, placeholders, and other attributes.
 */
readonly class Factory
{
    /**
     * Constructor to initialize dependencies.
     *
     * @param Translator $translator Translator for form translation.
     * @param User $user User object to check login status.
     */
    public function __construct(
        private Translator $translator,
        private User $user,
    ) {
    }


    /**
     * Creates and returns a form instance.
     *
     * If the user is logged in, adds protection to the form.
     * Sets the translator for form elements.
     *
     * @return Form The created form instance.
     */
    public function create(): Form
    {
        $form = new Form();

        // Add form protection if the user is logged in
        if ($this->user->isLoggedIn()) {
            $form->addProtection();
        }

        // Set the translator for form
        $form->setTranslator($this->translator);

        return $form;
    }


    /**
     * Adds a password input field to the form.
     *
     * This method adds a password input field with an optional minimum length validation.
     * It uses the addTextInput method with type 'password'.
     *
     * @param Form $form The form to which the input field will be added.
     * @return TextInput The created password input field.
     */
    public function addPassword(Form $form): TextInput
    {
        return $this->addTextInput(
            $form,
            name: 'password',
            label: 'Password',
            type: 'password',
            placeholder: 'Your password',
            required: 'Please enter your password.',
            rule: $form::MinLength,
            ruleMessage: 'Password must be at least %d characters long.',
            ruleValue: 6,
        );
    }


    /**
     * Adds a password verification input field to the form.
     *
     * This method adds a password confirmation field to check if the entered passwords match.
     *
     * @param Form $form The form to which the input field will be added.
     * @param string $ruleValue The name of the password field to compare against.
     * @return TextInput The created password verification input field.
     */
    public function addPasswordVerification(Form $form, string $ruleValue): TextInput
    {
        $passwordField = $this->addTextInput(
            $form,
            name: 'verify',
            label: 'Password to check',
            type: 'password',
            placeholder: 'Your password to check',
            required: 'Please enter the password to check.',
        );

        // Add a rule to check if the passwords match
        $passwordField->addRule($form::Equal, 'Passwords do not match.', $ruleValue);
        return $passwordField;
    }


    /**
     * Adds an email input field to the form.
     *
     * This method adds an email input field with email validation.
     *
     * @param Form $form The form to which the input field will be added.
     * @return TextInput The created email input field.
     */
    public function addEmail(Form $form): TextInput
    {
        return $this->addTextInput(
            $form,
            name: 'email',
            label: 'Email',
            type: 'email',
            placeholder: 'Your email address',
            required: 'Please enter your email address.',
            rule: $form::Email,
            ruleMessage: 'Please enter a valid email address.',
        );
    }


    /**
     * Generic method to add a text input field to the form.
     *
     * This method creates an input field based on the type provided. It supports text, password, and email fields.
     * It also allows setting optional placeholders, required fields, validation rules, and custom validation messages.
     *
     * @param Form $form The form to which the input field will be added.
     * @param string $name The name of the input field.
     * @param string $label The label for the input field.
     * @param string|null $type The type of the input field (e.g., 'text', 'password', 'email'). Default is 'text'.
     * @param string|null $placeholder The placeholder text for the input field.
     * @param string|null $required A message that will be displayed if the field is required.
     * @param string|null $rule The validation rule to apply (e.g., $form::Email, $form::MinLength).
     * @param string|null $ruleMessage The message to display for the validation rule.
     * @param string|int|null $ruleValue The value for the validation rule (optional, depending on the rule).
     * @return TextInput The created input field.
     */
    public function addTextInput(
        Form $form,
        string $name,
        string $label,
        ?string $type = 'text',
        ?string $placeholder = null,
        ?string $required = null,
        ?string $rule = null,
        ?string $ruleMessage = null,
        string|int|null $ruleValue = null,
    ): TextInput {

        // Create the appropriate input field based on the type
        $input = match ($type) {
            'password' => $form->addPassword($name, $label),
            'email' => $form->addText($name, $label)
                ->setHtmlAttribute('type', 'email'),

            default => $form->addText($name, $label),
        };

        // Set optional attributes if provided
        if ($placeholder !== null) {
            $input->setHtmlAttribute('placeholder', $placeholder);
        }

        if ($required !== null) {
            $input->setRequired($required);
        }

        // Apply validation rule if provided
        if ($rule) {
            $input->addRule($rule, $ruleMessage, $ruleValue);
        }

        return $input;
    }
}
