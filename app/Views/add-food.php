<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FoodSwipe — Ajouter un plat</title>
  <link rel="stylesheet" href="/css/style.css" />
</head>
<body>

<div class="app-page">

  <!-- Top Bar -->
  <div class="topbar">
    <span class="topbar-logo"><span>🍽️</span>FoodSwipe</span>
    <div class="topbar-actions">
      <a href="#" title="Se déconnecter" onclick="logout()">🚪</a>
    </div>
  </div>

  <!-- Form Body -->
  <div class="addfood-body">

    <!-- card de demo -->
    <div class="preview-wrap">
      <div class="preview-label">Aperçu</div>
      <div class="preview-card" id="preview-card">
        <div class="preview-img" id="preview-img" style="background:linear-gradient(135deg,#FF6B6B22,#FF6B6B55)">
          <img id="preview-photo" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;object-position:center" />
          <span id="preview-emoji">🍽️</span>
        </div>
        <div class="food-card-info">
          <div class="food-card-top">
            <div class="food-card-name" id="preview-name">Nom du plat</div>
            <div class="food-card-rating">⭐ <span id="preview-rating">-</span></div>
          </div>
          <div class="food-card-meta">
            <span class="badge category" id="preview-cat">Catégorie</span>
            <span class="badge time" id="preview-time">⏱ --</span>
            <span class="badge cal"  id="preview-cal">🔥 -- kcal</span>
          </div>
          <div class="food-card-desc" id="preview-desc">Description du plat…</div>
        </div>
      </div>
    </div>

    <!-- Form -->
    <form class="addfood-form" action="" method="post" enctype="multipart/form-data">

      <!-- Image Upload -->
      <div class="form-group">
        <label>Photo du plat</label>
        <div class="upload-zone" id="upload-zone"
             onclick="document.getElementById('field-img').click()"
             ondragover="onDragOver(event)" ondragleave="onDragLeave(event)" ondrop="onDrop(event)">
          <input type="file" id="field-img" name="field-img" accept="image/*" style="display:none" onchange="onImageUpload(event)" />
          <div class="upload-placeholder" id="upload-placeholder">
            <span class="upload-icon">📷</span>
            <p class="upload-text">Cliquer ou glisser une photo</p>
            <p class="upload-hint">JPG, PNG, WEBP · max 5 Mo</p>
          </div>
          <div class="upload-preview" id="upload-preview" style="display:none">
            <img id="upload-preview-img" src="" alt="Aperçu" />
            <button type="button" class="upload-remove" onclick="removeImage(event)" title="Supprimer la photo">✕</button>
          </div>
        </div>
      </div>

      <!-- Emoji Picker -->
      <div class="form-group">
        <label>Emoji du plat</label>
        <div class="emoji-grid" id="emoji-grid">
          <?php foreach (($emojis ?? []) as $emoji): ?>
            <button type="button" class="emoji-btn" data-emoji="<?php echo esc($emoji); ?>"><?php echo esc($emoji); ?></button>
          <?php endforeach; ?>
        </div>
        <input type="hidden" id="field-emoji" name="field-emoji" value="🍽️" />
      </div>

      <!-- Name -->
      <div class="form-group">
        <label>Nom du plat <span class="required">*</span></label>
        <input type="text" id="field-name" name="field-name" placeholder="ex : Bœuf bourguignon" maxlength="40"
               oninput="syncPreview()" required />
      </div>

      <!-- Category -->
      <div class="form-group">
        <label>Catégorie <span class="required">*</span></label>
        <div class="select-wrap">
          <select id="field-cat" name="field-cat" onchange="onCatChange()" required>
            <option value="">-- Choisir --</option>
            <?php foreach (($categories ?? []) as $category): ?>
              <option><?php echo esc($category); ?></option>
            <?php endforeach; ?>
            <option value="__custom__">Autre (préciser)</option>
          </select>
        </div>
        <input type="text" id="field-cat-custom" name="field-cat-custom" placeholder="Votre catégorie…"
               style="display:none;margin-top:8px" oninput="syncPreview()" />
      </div>

      <!-- Time + Calories row -->
      <div class="form-row">
        <div class="form-group" style="flex:1">
          <label>Temps <span class="required">*</span></label>
          <div class="input-suffix-wrap">
                 <input type="number" id="field-time" name="field-time" placeholder="30" min="1" max="999"
                   oninput="syncPreview()" required />
            <span class="input-suffix">min</span>
          </div>
        </div>
        <div class="form-group" style="flex:1">
          <label>Calories <span class="required">*</span></label>
          <div class="input-suffix-wrap">
                 <input type="number" id="field-cal" name="field-cal" placeholder="500" min="1" max="9999"
                   oninput="syncPreview()" required />
            <span class="input-suffix">kcal</span>
          </div>
        </div>
      </div>

      <!-- Rating -->
      <div class="form-group">
        <label>Note  <span class="required">*</span></label>
        <div class="star-row">
             <input type="range" id="field-rating" name="field-rating" min="1" max="5" step="0.1" value="4.0"
                 oninput="syncPreview()" />
          <div class="star-display">
            <span id="star-visual">★★★★☆</span>
            <span id="star-num" class="star-num">4.0</span>
          </div>
        </div>
      </div>

      <!-- Description -->
      <div class="form-group">
        <label>Description</label>
        <textarea id="field-desc" name="field-desc" placeholder="Décrivez votre plat en quelques mots…"
                  rows="3" maxlength="140" oninput="syncPreview()"></textarea>
        <div class="char-count"><span id="char-count">0</span>/140</div>
      </div>

      <?php if (!empty($success)): ?>
        <p class="form-success visible" id="form-success"><?php echo esc($success); ?></p>
      <?php else: ?>
        <p class="form-success" id="form-success"></p>
      <?php endif; ?>

      <?php if (!empty($error)): ?>
        <p class="form-error visible" id="form-error"><?php echo esc($error); ?></p>
      <?php else: ?>
        <p class="form-error" id="form-error"></p>
      <?php endif; ?>

      <button type="submit" class="btn-primary">Ajouter le plat ✅</button>

    </form>

  </div>

  <!-- Bottom Nav -->
  <div class="bottom-nav">
    <a href="/home">
      <span class="nav-icon">🔥</span>Découvrir
    </a>
    <a href="/ajout" class="active">
      <span class="nav-icon">➕</span>Ajouter
    </a>
    <a href="/stats">
      <span class="nav-icon">📊</span>Mes stats
    </a>
  </div>

</div>

<!-- Success Toast -->
<div class="toast" id="toast">✅ Plat ajouté avec succès !</div>

<script>
  window.FOOD_EMOJIS = <?php echo json_encode($emojis ?? [], JSON_UNESCAPED_UNICODE); ?>;
  window.FOOD_CATEGORIES = <?php echo json_encode($categories ?? [], JSON_UNESCAPED_UNICODE); ?>;
  window.FOOD_SUCCESS = <?php echo json_encode($success ?? '', JSON_UNESCAPED_UNICODE); ?>;

  if (localStorage.getItem('fs_logged') !== 'true') {
    window.location.href = 'login.html';
  }
  function logout() {
    localStorage.setItem('fs_logged', 'false');
    window.location.href = 'login.html';
  }

  function showToast() {
    const t = document.getElementById('toast');
    if (!t) return;
    t.classList.add('visible');
    setTimeout(() => t.classList.remove('visible'), 2500);
  }

  if (window.FOOD_SUCCESS) {
    showToast();
  }

  /* ── Emoji grid ── */
  const EMOJIS = Array.isArray(window.FOOD_EMOJIS) ? window.FOOD_EMOJIS : [];

  const CAT_COLORS = [
    '#FF6B6B','#FF8E53','#FFC371','#4ECDC4','#45B7D1',
    '#96CEB4','#DDA0DD','#FF69B4','#20B2AA','#9370DB','#F08080','#3CB371',
  ];
  const CAT_LIST = Array.isArray(window.FOOD_CATEGORIES) ? window.FOOD_CATEGORIES : [];
  const catColor = cat => {
    const i = CAT_LIST.indexOf(cat);
    return CAT_COLORS[i >= 0 ? i : CAT_COLORS.length - 1];
  };

  let selectedEmoji = null;
  let uploadedImageDataURL = null;

  /* ── Image upload ── */
  function onImageUpload(e) {
    const file = e.target.files[0];
    if (file) processImageFile(file);
  }

  function onDragOver(e) {
    e.preventDefault();
    document.getElementById('upload-zone').classList.add('drag-over');
  }

  function onDragLeave(e) {
    document.getElementById('upload-zone').classList.remove('drag-over');
  }

  function onDrop(e) {
    e.preventDefault();
    document.getElementById('upload-zone').classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) processImageFile(file);
  }

  function processImageFile(file) {
    const err = document.getElementById('form-error');
    if (file.size > 5 * 1024 * 1024) {
      err.textContent = 'L\'image dépasse 5 Mo. Choisissez un fichier plus léger.';
      err.classList.add('visible');
      return;
    }
    err.classList.remove('visible');

    const reader = new FileReader();
    reader.onload = function(ev) {
      uploadedImageDataURL = ev.target.result;
      document.getElementById('upload-preview-img').src = uploadedImageDataURL;
      document.getElementById('upload-placeholder').style.display = 'none';
      document.getElementById('upload-preview').style.display    = 'block';
      syncPreview();
    };
    reader.readAsDataURL(file);
  }

  function removeImage(e) {
    e.stopPropagation();
    uploadedImageDataURL = null;
    document.getElementById('field-img').value = '';
    document.getElementById('upload-preview-img').src = '';
    document.getElementById('upload-preview').style.display    = 'none';
    document.getElementById('upload-placeholder').style.display = 'flex';
    syncPreview();
  }

  const grid = document.getElementById('emoji-grid');
  let emojiButtons = Array.from(grid.querySelectorAll('.emoji-btn'));

  if (emojiButtons.length === 0) {
    EMOJIS.forEach(em => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'emoji-btn';
      btn.textContent = em;
      grid.appendChild(btn);
    });
    emojiButtons = Array.from(grid.querySelectorAll('.emoji-btn'));
  }

  grid.addEventListener('click', e => {
    const btn = e.target.closest('.emoji-btn');
    if (!btn) return;
    const em = btn.dataset.emoji || btn.textContent.trim();
    selectEmoji(em, btn);
  });

  const DEFAULT_EMOJI = EMOJIS[0]
    || (emojiButtons[0] ? (emojiButtons[0].dataset.emoji || emojiButtons[0].textContent.trim()) : '🍽️');

  selectedEmoji = DEFAULT_EMOJI;

  document.getElementById('field-emoji').value = DEFAULT_EMOJI;
  document.getElementById('preview-emoji').textContent = DEFAULT_EMOJI;
  if (emojiButtons[0]) selectEmoji(DEFAULT_EMOJI, emojiButtons[0]);

  function selectEmoji(em, btn) {
    document.querySelectorAll('.emoji-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    selectedEmoji = em;
    document.getElementById('field-emoji').value = em;
    syncPreview();
  }

  /* ── Category custom field ── */
  function onCatChange() {
    const sel    = document.getElementById('field-cat');
    const custom = document.getElementById('field-cat-custom');
    custom.style.display = sel.value === '__custom__' ? 'block' : 'none';
    syncPreview();
  }

  function getCategory() {
    const sel = document.getElementById('field-cat');
    return sel.value === '__custom__'
      ? document.getElementById('field-cat-custom').value.trim()
      : sel.value;
  }

  /* ── Live preview sync ── */
  function syncPreview() {
    const name    = document.getElementById('field-name').value.trim()  || 'Nom du plat';
    const cat     = getCategory()                                         || 'Catégorie';
    const time    = document.getElementById('field-time').value;
    const cal     = document.getElementById('field-cal').value;
    const rating  = parseFloat(document.getElementById('field-rating').value).toFixed(1);
    const desc    = document.getElementById('field-desc').value.trim()   || 'Description du plat…';
    const emoji   = selectedEmoji;
    const col     = catColor(cat);

    // Photo vs emoji dans l'aperçu
    const previewPhoto = document.getElementById('preview-photo');
    const previewEmoji = document.getElementById('preview-emoji');
    if (uploadedImageDataURL) {
      previewPhoto.src             = uploadedImageDataURL;
      previewPhoto.style.display   = 'block';
      previewEmoji.style.display   = 'none';
      document.getElementById('preview-img').style.background = 'none';
    } else {
      previewPhoto.style.display   = 'none';
      previewEmoji.style.display   = 'block';
      document.getElementById('preview-img').style.background =
        `linear-gradient(135deg,${col}22,${col}55)`;
    }

    document.getElementById('preview-emoji').textContent  = emoji;
    document.getElementById('preview-name').textContent   = name;
    document.getElementById('preview-cat').textContent    = cat;
    document.getElementById('preview-time').textContent   = `⏱ ${time || '--'} min`;
    document.getElementById('preview-cal').textContent    = `🔥 ${cal  || '--'} kcal`;
    document.getElementById('preview-rating').textContent = rating;
    document.getElementById('preview-desc').textContent   = desc;

    // Stars
    const stars = Math.round(parseFloat(rating));
    document.getElementById('star-visual').textContent = '★'.repeat(stars) + '☆'.repeat(5 - stars);
    document.getElementById('star-num').textContent    = rating;

    // Char count
    const len = document.getElementById('field-desc').value.length;
    document.getElementById('char-count').textContent = len;
  }

  /* ── Init preview ── */
  syncPreview();
</script>

</body>
</html>
