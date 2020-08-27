<!-- Main section -->
<main>

  <!-- New member form -->
  <h2 class="member-title">Ajouter un(e) Argonaute</h2>
  <form class="new-member-form" method="POST">
    <label for="name">Nom de l&apos;Argonaute</label>
    <input id="name" name="name" type="text" placeholder="Charalampos" />
    <button type="submit" name='submit' class="submit">Envoyer</button>
  </form>

  <!-- Member list -->
  <h2>Membres de l'équipage</h2>">
    <ul class="member-list">
      <?php foreach ($argonautes as $argonaute) : ?>
        <li class="member-item"><?= $argonaute->getName() ?> </li>
      <?php endforeach ?>
    </ul>
</main>

<script src="<?= $assetsBaseUri ?>/js/app.js"></script>