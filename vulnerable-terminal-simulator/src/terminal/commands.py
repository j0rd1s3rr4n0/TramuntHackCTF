def list_challenges():
    challenges = ["reto_1", "reto_2", "reto_3", "reto_4"]
    return challenges

def show_user_info(user_name, level):
    return f"Usuario: {user_name}, Nivel de privilegios: {level}"

def execute_challenge(challenge_name, user_level):
    challenge_num = int(challenge_name.split("_")[1])
    if user_level < challenge_num:
        return f"No tienes suficientes privilegios para ejecutar {challenge_name}"
    return f"Ejecutando {challenge_name}..."

def help_command():
    return {
        "help": "Muestra este listado de comandos.",
        "whoami": "Muestra el nombre del usuario actual y su nivel.",
        "challenges": "Muestra la lista de challenges disponibles.",
        "ls": "Similar a 'challenges', muestra los challenges disponibles.",
        "bash": "Ejecuta un reto específico de la lista.",
        "sudo": "Permite ejecutar los challenges con privilegios elevados."
    }