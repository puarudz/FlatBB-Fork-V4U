<?php /** New password form. Variables: token, user */ ?>
<div class="auth-box">
  <header class="auth-head">
    <span class="auth-mark" aria-hidden="true"><?= icon('lock') ?></span>
    <div class="auth-heading">
      <h1><?= t('Choose a new password') ?></h1>
      <p class="auth-sub"><?= h(t('Account: %s', (string)$user['username'])) ?></p>
    </div>
  </header>
  <form method="post" action="<?= h(url('/reset/' . $token)) ?>">
    <?= csrf_field() ?>
    <div class="form-row"><label for="auth-reset-password"><?= t('New password') ?></label><div class="pw-wrap"><input id="auth-reset-password" type="password" name="password" required minlength="<?= h((string)password_min()) ?>" autofocus autocomplete="new-password" aria-describedby="auth-reset-help"><button type="button" class="pw-toggle" data-pw-toggle aria-label="<?= t('Show password') ?>"><?= icon('eye') ?><?= icon('eye-off') ?></button></div><div class="form-help" id="auth-reset-help"><?= t('At least %d characters.', password_min()) ?></div></div>
    <button type="submit" class="btn btn-primary btn-block auth-submit"><?= t('Save password') ?></button>
  </form>
</div>
