<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
(function () {
  // СЮДА добавь селекторы твоих кнопок/иконок, которые открывают меню,
  // чтобы клик по ним не закрывал меню сразу:
  const TOGGLE_SELECTORS = ['.um-dropdown-toggle', '[data-um-dropdown-toggle]', '[aria-haspopup="menu"]'];

  const isToggle = (el) => TOGGLE_SELECTORS.some(sel => el.closest(sel));

  const closeAllDropdowns = () => {
    document.querySelectorAll('.um-dropdown').forEach(dd => {
      dd.classList.remove('active','open','is-open','show');
      dd.style.display = 'none';
      dd.setAttribute('aria-hidden', 'true');
    });
  };

  // Клик где угодно на странице (capture = true — сработает раньше «stopPropagation»)
  document.addEventListener('click', function (e) {
    // если клик внутри меню — ничего не делаем
    if (e.target.closest('.um-dropdown')) return;

    // если клик по кнопке-открывалке — не закрываем
    if (isToggle(e.target)) return;

    // иначе закрываем все dropdown'ы
    closeAllDropdowns();
  }, true);

  // Закрытие по Esc
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeAllDropdowns();
  });
})();</script>
<!-- end Simple Custom CSS and JS -->
