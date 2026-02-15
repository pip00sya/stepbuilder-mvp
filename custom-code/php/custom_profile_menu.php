<?php

// 1. Регистрируем стили и скрипты
add_action('wp_enqueue_scripts', function () {
    wp_register_style('custom-profile-menu-style', false);
    wp_enqueue_style('custom-profile-menu-style');
    wp_add_inline_style('custom-profile-menu-style', '
       .custom-profile-menu-wrapper {
  position: relative;
  display: inline-block;
}

.profile-avatar-btn {
  background: #fff;
  border: none;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: box-shadow 0.2s;
  padding: 0;
}
.profile-avatar-btn:hover {
  box-shadow: 0 4px 16px rgba(0,0,0,0.09);
}

.profile-avatar-img {
  width: 59px;
  height: 59px;
  border-radius: 50%;
  object-fit: cover;
  background: #f0f0f0;
}

.profile-menu-avatar {
  width: 49px;
  height: 49px;
  border-radius: 50%;
  object-fit: cover;
  background: #f0f0f0;
}

.profile-avatar-letter,
.profile-menu-avatar-letter {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #e0e0e0;
  color: #555;
  font-size: 1.5rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
}

.profile-avatar-empty,
.profile-menu-avatar-empty {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #f0f0f0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.profile-menu {
  display: none;
  position: absolute;
  top: 0;
  right: 0;
  min-width: 260px;
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.06);
  padding: 16px 0 8px 0;
  z-index: 100;
  animation: fadeIn 0.2s;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px);}
  to { opacity: 1; transform: translateY(0);}
}

.profile-menu-header {
  display: flex;
  align-items: center;
  padding: 0 20px 8px 20px;
  gap: 12px;
}

.profile-menu-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: center;
}

.profile-menu-name {
  font-weight: 600;
  font-size: 1.05rem;
  color: #333842;
  margin-bottom: 2px;
}

.profile-menu-email {
  font-size: 0.92rem;
  color: #888;
  opacity: 0.8;
}

.profile-menu-divider {
  border: none;
  border-top: 1px solid #e6e6e6;
  margin: 10px 20px;
}

.profile-menu-item {
  width: calc(100% - 32px);
  margin: 0px 10px 6px 10px;
  border: none;
  border-radius: 12px;
  padding: 9px calc(100% - 22px) 9px 2px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 1rem;
  color: #333842;
  cursor: pointer;
  transition: background 0.18s, color 0.18s;
  background: #fff;
}
.profile-menu-item:hover {
  background: #f8f8f8;
}

.profile-menu-icon {
  display: flex;
  align-items: center;
  justify-content: center;
}
.profile-menu-bg {
  display: none;
}
.profile-menu-close {
  display: none;
}
@media (max-width: 500px) {
  .custom-profile-menu-wrapper {
    position: static;
  }
  .profile-avatar-btn {
    width: 48px;
    height: 48px;
  }
  .profile-menu-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  justify-content: center;
}
  .profile-avatar-img {
    width: 47px;
    height: 47px;
  }
  .profile-menu {
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    min-width: unset;
    width: 100vw;
    height: 100vh;
    border-radius: 0;
    padding: 24px 0 0 0;
    box-shadow: none;
    z-index: 1001;
    animation: fadeIn 0.2s;
    display: none;
  }
  .profile-menu-header {
    padding: 0 16px 8px 16px;
  }
  .profile-menu-item {
    margin: 0 10px 6px 10px;
    font-size: 1.1rem;
    padding: 12px 10px;
  }
  .profile-menu-close {
    display: block;
    position: absolute;
    top: 18px;
    left: 18px;
    background: none;
    border: none;
    font-size: 2rem;
    color: #888;
    z-index: 1002;
    cursor: pointer;
  }
  .profile-menu-bg {
    display: block;
    position: fixed;
    z-index: 1000;
    inset: 0;
  }
}
    ');

    wp_register_script('custom-profile-menu-script', false);
    wp_enqueue_script('custom-profile-menu-script');
    wp_add_inline_script('custom-profile-menu-script', "
document.addEventListener('DOMContentLoaded', function() {
  const avatarBtn = document.getElementById('profileAvatarBtn');
  const menu = document.getElementById('profileMenu');
  const menuBg = document.getElementById('profileMenuBg');
  const menuClose = document.getElementById('profileMenuClose');
  if (!avatarBtn || !menu || !menuBg || !menuClose) return;

function openMenu() {
  menu.style.display = 'block';
  menuBg.style.display = 'block';
  if (window.innerWidth <= 500) {
    document.body.style.overflow = 'hidden';
  }
}
function closeMenu() {
  menu.style.display = 'none';
  menuBg.style.display = 'none';
  document.body.style.overflow = '';
}

  avatarBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    if (menu.style.display === 'block') {
      closeMenu();
    } else {
      openMenu();
    }
  });
  menuClose.addEventListener('click', closeMenu);
  menuBg.addEventListener('click', closeMenu);

  document.addEventListener('click', function(e) {
    if (!menu.contains(e.target) && e.target !== avatarBtn) {
      closeMenu();
    }
  });
});
    ");
});

// 2. Шорткод для вывода меню
add_shortcode('custom_profile_menu', function () {
    if (!is_user_logged_in())
        return '';

    $current_user = wp_get_current_user();
    $user_name = $current_user->display_name ?: '';
    $user_email = $current_user->user_email ?: '';
    $avatar_url = get_avatar_url($current_user->ID, ['size' => 96]);
    $has_avatar = !empty($avatar_url) && strpos($avatar_url, 'gravatar.com/avatar/?d=mp') === false;
    $first_letter = $user_name ? mb_strtoupper(mb_substr($user_name, 0, 1)) : '';

    ob_start();
?>
    <div class="custom-profile-menu-wrapper">
      <button class="profile-avatar-btn" id="profileAvatarBtn" type="button">
        <?php if ($has_avatar): ?>
          <img src="<?php echo esc_url($avatar_url); ?>" alt="Avatar" class="profile-avatar-img">
        <?php
    elseif ($first_letter): ?>
          <span class="profile-avatar-letter"><?php echo esc_html($first_letter); ?></span>
        <?php
    else: ?>
          <span class="profile-avatar-empty"></span>
        <?php
    endif; ?>
      </button>
		<div class="profile-menu-bg" id="profileMenuBg"></div>
      <div class="profile-menu" id="profileMenu">
		  <button class="profile-menu-close" id="profileMenuClose" aria-label="Close">&times;</button>
        <div class="profile-menu-header">
          <div class="profile-menu-info">
            <div class="profile-menu-name"><?php echo esc_html($user_name); ?></div>
            <div class="profile-menu-email"><?php echo esc_html($user_email); ?></div>
          </div>
			<?php if ($has_avatar): ?>
            <img src="<?php echo esc_url($avatar_url); ?>" alt="Avatar" class="profile-menu-avatar">
          <?php
    elseif ($first_letter): ?>
            <span class="profile-menu-avatar profile-menu-avatar-letter"><?php echo esc_html($first_letter); ?></span>
          <?php
    else: ?>
            <span class="profile-menu-avatar profile-menu-avatar-empty"></span>
          <?php
    endif; ?>
        </div>
        <hr class="profile-menu-divider">
        <button class="profile-menu-item" onclick="window.location.href='<?php echo esc_url(home_url('/')); ?>'">
          <span class="profile-menu-icon">
            <!-- Home icon SVG -->
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9.5L10 4l7 5.5V17a1 1 0 0 1-1 1h-3a1 1 0 0 1-1-1v-3H8v3a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5z"/></svg>
          </span>
          <span>Home</span>
        </button>
		  		  		          <button class="profile-menu-item" onclick="window.location.href='<?php echo esc_url(home_url('/about')); ?>'">
<span class="profile-menu-icon">
  <!-- About/Info icon SVG (буква i) -->
  <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="10" cy="10" r="8"/>
    <rect x="9" y="8" width="2" height="7" rx="1" fill="currentColor" stroke="none"/>
    <circle cx="10" cy="6" r="1" fill="currentColor" stroke="none"/>
  </svg>
</span>
          <span>About</span>
        </button>
        <button class="profile-menu-item" onclick="window.location.href='https://stepbuilder.org/account/'">
          <span class="profile-menu-icon">
            <!-- Pencil/Edit icon SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
</svg>
          </span>
          <span>Account</span>
        </button>
        <button class="profile-menu-item" onclick="window.location.href='<?php echo esc_url(home_url('/user')); ?>'">
<span class="profile-menu-icon">
  <!-- User/Person icon SVG -->
  <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="10" cy="7" r="4"/>
    <path d="M3 17c0-2.8 4-4 7-4s7 1.2 7 4"/>
  </svg>
</span>
          <span>Profile</span>
        </button>
		          <button class="profile-menu-item" onclick="window.location.href='<?php echo esc_url(home_url('/documents')); ?>'">
          <span class="profile-menu-icon">
            <!-- Library/Portfolio icon SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="-1 1 24 24" fill="none" 
     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M6 2h9l5 5v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"/>
  <polyline points="14 2 14 8 20 8"/>
  <line x1="9" y1="13" x2="15" y2="13"/>
  <line x1="9" y1="17" x2="15" y2="17"/>
  <line x1="9" y1="9" x2="10" y2="9"/>
</svg>
          </span>
          <span>Documents</span>
        </button>
		          <button class="profile-menu-item" onclick="window.location.href='<?php echo esc_url(home_url('/guides')); ?>'">
          <span class="profile-menu-icon">
            <!-- Library/Portfolio icon SVG -->
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="14" height="10" rx="2"/><path d="M7 7V5a3 3 0 0 1 6 0v2"/></svg>
          </span>
          <span>Guides</span>
        </button>
        <hr class="profile-menu-divider">
        <button class="profile-menu-item profile-menu-logout" onclick="window.location.href='<?php echo esc_url(wp_logout_url(home_url())); ?>'">
          <span class="profile-menu-icon">
            <!-- Logout icon SVG -->
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M16 17v1a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v1" />
  <path d="M21 12H9" />
  <path d="M18 9l3 3-3 3" />
</svg>
          </span>
          <span>Logout</span>
        </button>
      </div>
    </div>
    <?php
    return ob_get_clean();
});