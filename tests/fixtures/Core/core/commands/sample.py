# Minimal stand-in for fman's command classes, used only by GeneratorTest so the
# test suite stays hermetic (no dependency on a real fman checkout).

class MoveCursorDown(DirectoryPaneCommand):
	aliases = ('Move cursor down', 'Down')

	def __call__(self):
		pass


class CommandPalette(ApplicationCommand):
	aliases = ('Command palette',)

	def __call__(self):
		pass


# Note: no `Copy` class here on purpose — the generator must fall back to a
# humanized name ("Copy") for the `copy` command.
