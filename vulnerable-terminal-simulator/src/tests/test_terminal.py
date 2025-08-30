import unittest
from terminal.commands import show_whoami, show_challenges, run_bash, run_sudo
from terminal.privilege_escalation import PrivilegeEscalationGame

class TestTerminal(unittest.TestCase):

    def setUp(self):
        self.game = PrivilegeEscalationGame()

    def test_show_whoami(self):
        expected_output = f"\033[92m{self.game.user_name}\033[97m "
        with self.assertLogs(level='INFO') as log:
            show_whoami([])
        self.assertIn(expected_output, log.output)

    def test_show_challenges(self):
        expected_output = "\033[93mLista de challenges disponibles:"
        with self.assertLogs(level='INFO') as log:
            show_challenges([])
        self.assertIn(expected_output, log.output)

    def test_run_bash_success(self):
        self.game.level = 1
        self.game.solved_challenges[1] = False
        with self.assertLogs(level='INFO') as log:
            run_bash(['reto_1'])
        self.assertIn("\033[92m¡Has resuelto 1 exitosamente!", log.output)

    def test_run_bash_insufficient_privileges(self):
        self.game.level = 1
        with self.assertLogs(level='INFO') as log:
            run_bash(['reto_2'])
        self.assertIn("\033[91mNo tienes suficientes privilegios para ejecutar reto_2", log.output)

    def test_run_sudo(self):
        self.game.level = 2
        self.game.solved_challenges[1] = False
        with self.assertLogs(level='INFO') as log:
            run_sudo(['reto_1'])
        self.assertIn("\033[92m¡Has resuelto 1 exitosamente!", log.output)

if __name__ == '__main__':
    unittest.main()