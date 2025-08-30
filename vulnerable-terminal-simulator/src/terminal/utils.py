def typewriter_effect(text, delay=0.01):
    """Imprime el texto como si fuera una máquina de escribir."""
    import time
    for char in text:
        print(char, end='', flush=True)
        time.sleep(delay)
    print()  # Nueva línea al final

def validate_command_format(args, expected_length):
    """Valida el formato del comando ingresado."""
    return len(args) == expected_length and all(arg for arg in args)