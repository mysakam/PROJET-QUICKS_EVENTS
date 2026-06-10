<?php

class Validator
{
    private array $errors = [];
    private array $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function required(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        $value = trim((string) ($this->data[$field] ?? ''));

        if ($value === '') {
            $this->errors[$field] = "$label est requis.";
        }

        return $this;
    }

    public function email(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        $value = $this->data[$field] ?? '';

        if (!$value || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "$label doit être un email valide.";
        }

        return $this;
    }

    public function min(string $field, int $length, string $label = ''): self
    {
        $label = $label ?: $field;
        $value = (string) ($this->data[$field] ?? '');

        if (strlen($value) > 0 && strlen($value) < $length) {
            $this->errors[$field] = "$label doit contenir au moins $length caracteres.";
        }

        return $this;
    }

    public function max(string $field, int $length, string $label = ''): self
    {
        $label = $label ?: $field;
        $value = (string) ($this->data[$field] ?? '');

        if (strlen($value) > $length) {
            $this->errors[$field] = "$label ne peut pas depasser $length caracteres.";
        }

        return $this;
    }

    public function numeric(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        $value = $this->data[$field] ?? '';

        if ($value !== '' && !is_numeric($value)) {
            $this->errors[$field] = "$label doit être numerique.";
        }

        return $this;
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function fails(): bool
    {
        return !$this->passes();
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function error(string $field): ?string
    {
        return $this->errors[$field] ?? null;
    }
}
