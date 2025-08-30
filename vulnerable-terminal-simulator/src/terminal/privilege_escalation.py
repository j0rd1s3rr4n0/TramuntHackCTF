class PrivilegeEscalation:
    def __init__(self):
        self.level = 1  # Nivel de privilegios inicial
        self.solved_challenges = {1: False, 2: False, 3: False, 4: False}

    def solve_challenge(self, challenge_number):
        """Simula la resolución de un reto y la escalada de privilegios."""
        if challenge_number < 1 or challenge_number > 4:
            raise ValueError("Número de reto inválido. Debe estar entre 1 y 4.")
        
        if self.solved_challenges[challenge_number]:
            return f"Ya has resuelto el reto {challenge_number}."

        self.solved_challenges[challenge_number] = True
        self.level += 1
        return f"¡Has resuelto el reto {challenge_number} exitosamente! Tu nivel ahora es: {self.level}"

    def check_privileges(self, required_level):
        """Verifica si el usuario tiene suficientes privilegios."""
        return self.level >= required_level

    def get_current_level(self):
        """Devuelve el nivel actual de privilegios del usuario."""
        return self.level

    def reset_challenges(self):
        """Resetea los desafíos resueltos y el nivel de privilegios."""
        self.solved_challenges = {1: False, 2: False, 3: False, 4: False}
        self.level = 1
        return "Desafíos reseteados. Nivel de privilegios restablecido a 1."