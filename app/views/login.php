<?php /** Sign-in form. Variable: back. Region: auth.login.extra */ ?>
<div class="auth-box">
  <header class="auth-head">
    <span class="auth-mark" aria-hidden="true"><?= icon('lock') ?></span>
    <div class="auth-heading">
      <h1><?= t('Sign in') ?></h1>
      <p class="auth-sub"><?= h(t('Welcome back')) ?></p>
    </div>
  </header>
  <form method="post" action="<?= h(url('/login', $back !== '' ? ['back' => $back] : [])) ?>">
    <?= csrf_field() ?>
    <div class="form-row"><label for="auth-username"><?= t('Username or email') ?></label><input id="auth-username" type="text" name="username" required autofocus autocomplete="username"></div>
    <div class="form-row"><label for="auth-password"><?= t('Password') ?></label><div class="pw-wrap"><input id="auth-password" type="password" name="password" required autocomplete="current-password"><button type="button" class="pw-toggle" data-pw-toggle aria-label="<?= t('Show password') ?>"><?= icon('eye') ?><?= icon('eye-off') ?></button></div></div>
    <div class="auth-options"><?= checkbox('remember', true, t('Keep me signed in')) ?><a class="auth-link" href="<?= h(url('/forgot')) ?>"><?= t('Forgot password?') ?></a></div>
    <?= region('auth.login.extra') ?>
    <button type="submit" class="btn btn-primary btn-block auth-submit"><?= t('Sign in') ?></button>
  </form>
  <?php $after = (string)hook('auth.login.after', '', ['back' => $back]); ?>
  <?php if ($after !== ''): ?><div class="auth-extra"><?= raw($after) ?></div><?php endif; ?>
  <?php if (setting('allow_register', '1') === '1'): ?><p class="auth-alt"><?= t('No account yet?') ?> <a href="<?= h(url('/register')) ?>"><?= t('Create one') ?></a></p><?php endif; ?>
</div>
