<!doctype html>
<html lang="<?= langSetting('lang') ?: 'en'; ?>" dir="<?= langSetting('dir') ?: 'ltr'; ?>">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= (isset($pageTitle) ? $pageTitle : 'PureEasyPHP'); ?></title>

  <?php if (langSetting('dir') === 'rtl') { ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.rtl.min.css"
      integrity="sha384-DOXMLfHhQkvFFp+rWTZwVlPVqdIhpDVYT9csOnHSgWQWPX0v5MCGtjCJbY6ERspU" crossorigin="anonymous">
  <?php } else { ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <?php } ?>
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?= baseUrl(''); ?>">PureEasyPHP</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

          <li class="nav-item">
            <a class="nav-link <?= isPageActive('') ?>" href="<?= baseUrl(''); ?>">
              <?= translate('nav_home'); ?>
            </a>
          </li>

          <?php if (isLogin()) { ?>
            <li class="nav-item">
              <a class="nav-link <?= isPageActive(adminLink()) ?>" href="<?= baseUrl(adminLink()); ?>">
                <?= translate('nav_dashboard'); ?>
              </a>
            </li>
          <?php } else { ?>
            <li class="nav-item">
              <a class="nav-link <?= isPageActive(authLink()) ?>" href="<?= baseUrl(authLink()); ?>">
                <?= translate('nav_auth'); ?>
              </a>
            </li>
          <?php } ?>

        </ul>
        <div class="d-flex justify-content-end">
          <!-- language_switcher.php -->
          <form method="post" action="change_language" class="d-flex align-items-center gap-2">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken(); ?>">
            <select name="language" id="language" class="form-select" aria-label="Select Language"
              onchange="this.form.submit()">
              <option value="en" <?= currentLanguage() == 'en' ? 'selected' : '' ?>>English</option>
              <option value="ar" <?= currentLanguage() == 'ar' ? 'selected' : '' ?>>العربية</option>
            </select>
          </form>
        </div>
      </div>
    </div>
  </nav>

  <div class="container my-4">
    <?= $content; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>