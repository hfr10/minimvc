<h1>Liste des utilisateurs</h1>

    <?php if (!empty($users)) : ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <a class="btn" href="edit.php?id=<?= $user['id'] ?>">Modifier</a>
                            <a class="btn" href="delete.php?id=<?= $user['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Aucun utilisateur trouvé.</p>
    <?php endif; ?>
