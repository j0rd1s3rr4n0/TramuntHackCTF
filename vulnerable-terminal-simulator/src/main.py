import time
import os
import sys
from tqdm import tqdm
from flask import Flask, request, jsonify
from colorama import Fore, Style
from terminal.commands import show_welcome, show_prompt, execute_command

class Colors:
    RESET = "\033[0m"
    RED = "\033[31m"
    GREEN = "\033[32m"
    YELLOW = "\033[33m"
    BLUE = "\033[34m"
    MAGENTA = "\033[35m"
    CYAN = "\033[36m"
    WHITE = "\033[37m"
    BOLD = "\033[1m"

class PrivilegeEscalationGame:
    def __init__(self):
        self.user_name = "j0rd1s3rr4n0"
        self.level = 1  # Nivel de privilegios (1=usuario, 2=privilegios limitados, 3=privilegios elevados, 4=root)
        self.running = True
        self.solved_challenges = {1: False, 2: False, 3: False, 4: False}
        self.commands = {
            "help": self.show_help,
            "whoami": self.show_whoami,
            "ls": self.show_challenges,
            "bash": self.run_bash,
            "sudo": self.run_sudo,
        }

    def typewriter_effect(self, text, delay=0.02):
        for char in text:
            sys.stdout.write(char)
            sys.stdout.flush()
            time.sleep(delay)
        print()

    def loading_bar(self, step, total_steps, color):
        sys.stdout.write(f"\r{color}{step}{' ' * (26-len(step))}[{'=' * total_steps}{' ' * (50 - total_steps)}] {total_steps * 2}%{Colors.RESET}")
        sys.stdout.flush()
        time.sleep(0.1)

    def display_loading(self, steps):
        for step in steps:
            total_steps = 0
            while total_steps < 51:
                self.loading_bar(step, total_steps, Colors.YELLOW)
                total_steps += 1
            print()

    def show_welcome(self):
        self.typewriter_effect(f"{Colors.GREEN}Bienvenido {Colors.CYAN}{self.user_name}{Colors.RESET} al servidor C2 de {Colors.YELLOW}HackermanLand.{Colors.RESET}")
        self.typewriter_effect("Parece que alguien te ha dado acceso a este servidor...")
        self.typewriter_effect("Vamos a ver si logras escalar privilegios...")

    def show_help(self, args):
        self.typewriter_effect(f"{Colors.YELLOW}Comandos disponibles:")
        self.typewriter_effect(f"{Colors.CYAN}help{Colors.RESET} - Muestra este listado de comandos")
        self.typewriter_effect(f"{Colors.CYAN}whoami{Colors.RESET} - Muestra el nombre del usuario actual y el nivel")
        self.typewriter_effect(f"{Colors.CYAN}ls{Colors.RESET} - Muestra los retos disponibles")
        self.typewriter_effect(f"{Colors.CYAN}bash reto_X.sh{Colors.RESET} - Ejecuta el reto X")
        self.typewriter_effect(f"{Colors.CYAN}sudo bash reto_X.sh{Colors.RESET} - Ejecuta un reto con privilegios elevados")

    def show_whoami(self, args):
        self.typewriter_effect(f"{Colors.GREEN}{self.user_name}{Colors.RESET} - Nivel de privilegios: {self.level}")

    def show_challenges(self, args):
        challenges = ["reto_1", "reto_2", "reto_3", "reto_4"]
        self.typewriter_effect(f"{Colors.YELLOW}Lista de retos disponibles:")
        for i, reto in enumerate(challenges, 1):
            status = f"{Colors.GREEN}Resuelto{Colors.RESET}" if self.solved_challenges[i] else f"{Colors.RED}No resuelto{Colors.RESET}"
            self.typewriter_effect(f"{reto} - {status}")

    def run_bash(self, args):
        if len(args) != 1 or not args[0].startswith("reto_"):
            self.typewriter_effect(f"{Colors.RED}Formato incorrecto. Usa 'bash reto_X.sh'.{Colors.RESET}")
            return

        reto_num = int(args[0].split("_")[1])
        if self.level < reto_num:
            self.typewriter_effect(f"{Colors.RED}No tienes suficientes privilegios para ejecutar {args[0]}{Colors.RESET}")
            return

        if not self.solved_challenges[reto_num]:
            self.solve_reto(reto_num)
        else:
            self.typewriter_effect(f"{Colors.GREEN}Ya has resuelto {args[0]}. ¡Bien hecho!{Colors.RESET}")

    def run_sudo(self, args):
        if len(args) != 1 or not args[0].startswith("reto_"):
            self.typewriter_effect(f"{Colors.RED}Formato incorrecto. Usa 'sudo bash reto_X.sh'.{Colors.RESET}")
            return

        reto_num = int(args[0].split("_")[1])
        if self.level < reto_num:
            self.typewriter_effect(f"{Colors.RED}No tienes suficientes privilegios para ejecutar {args[0]}{Colors.RESET}")
            return

        self.typewriter_effect(f"{Colors.CYAN}Ejecutando {args[0]} con privilegios elevados...{Colors.RESET}")
        self.solve_reto(reto_num)

    def solve_reto(self, reto_num):
        steps = [
            "Analizando vulnerabilidad...",
            "Ejecutando exploit...",
            "Escalando privilegios...",
        ]
        self.display_loading(steps)

        self.solved_challenges[reto_num] = True
        self.level += 1
        self.typewriter_effect(f"{Colors.GREEN}¡Has resuelto el reto {reto_num} exitosamente!{Colors.RESET}")
        if reto_num == 4:
            self.typewriter_effect(f"{Colors.YELLOW}¡Felicidades! Has alcanzado el nivel root y completado todos los retos.{Colors.RESET}")

    def start(self):
        self.show_welcome()
        while self.running:
            command = input(f"{Colors.CYAN}{self.user_name}@hackermanland:~$ {Colors.RESET}").strip()
            if command == "exit":
                self.running = False
            else:
                parts = command.split()
                cmd = parts[0]
                args = parts[1:]
                if cmd in self.commands:
                    self.commands[cmd](args)
                else:
                    self.typewriter_effect(f"{Colors.RED}Comando desconocido. Usa 'help' para ver los comandos disponibles.{Colors.RESET}")

if __name__ == "__main__":
    game = PrivilegeEscalationGame()
    game.start()

app = Flask(__name__)

# Simulated file system
file_system = {
    "/": ["home", "var", "etc", "root"],
    "/home": ["j0rd1s3rr4n0"],
    "/home/j0rd1s3rr4n0": ["notes.txt", "backup"],
    "/home/j0rd1s3rr4n0/backup": ["old_config.bak"],
    "/var": ["log"],
    "/var/log": ["auth.log", "syslog"],
    "/etc": ["passwd", "sudoers"],
    "/root": ["flag.txt"],
}

current_dir = "/home/j0rd1s3rr4n0"

@app.route('/ls', methods=['GET'])
def list_directory():
    global current_dir
    contents = file_system.get(current_dir, [])
    return jsonify(contents)

@app.route('/cd', methods=['POST'])
def change_directory():
    global current_dir
    target_dir = request.json.get('dir')
    if target_dir == "..":
        if current_dir != "/":
            current_dir = "/".join(current_dir.rstrip("/").split("/")[:-1]) or "/"
    elif target_dir in file_system.get(current_dir, []):
        new_path = f"{current_dir}/{target_dir}".replace("//", "/")
        if new_path == "/root":
            return jsonify({"error": "Access denied to /root."}), 403
        current_dir = new_path
    else:
        return jsonify({"error": "Directory not found."}), 404
    return jsonify({"current_dir": current_dir})

@app.route('/cat', methods=['POST'])
def read_file():
    global current_dir
    file_name = request.json.get('file')
    if file_name == "notes.txt" and current_dir == "/home/j0rd1s3rr4n0":
        return jsonify({"content": "Revisa el archivo old_config.bak en /home/j0rd1s3rr4n0/backup."})
    elif file_name == "old_config.bak" and current_dir == "/home/j0rd1s3rr4n0/backup":
        return jsonify({"content": "Contraseña encontrada: P@ssw0rd123"})
    elif file_name == "flag.txt" and current_dir == "/root":
        return jsonify({"error": "No tienes permisos para leer este archivo."}), 403
    else:
        return jsonify({"error": "Archivo no encontrado."}), 404

@app.route('/escape', methods=['POST'])
def escape_terminal():
    if request.json.get('command') == "escape.sh" and current_dir == "/home/j0rd1s3rr4n0":
        return jsonify({"message": "¡Has encontrado una vulnerabilidad y escapado de la terminal!"})
    return jsonify({"error": "Archivo no encontrado o no ejecutable."}), 404

if __name__ == '__main__':
    app.run(debug=True)