const text = "Pray with Word of God";
const target = document.getElementById("banner-text");
const siteHeader = document.getElementById("site-header");
const headerMenu = document.getElementById("header-menu");
const headerMenuToggle = document.getElementById("header-menu-toggle");
const testimonyTabs = document.querySelectorAll(".testimony-tab");
const testimonyPanels = document.querySelectorAll(".testimony-panel");
const topicBrowserTabs = document.querySelectorAll(".topic-browser-tab");
const topicBrowserPanels = document.querySelectorAll(".topic-browser-panel");
const chapterTopicFilter = document.querySelector("[data-chapter-topic-filter]");

let index = 0;

function typeWriter() {
  if (!target) {
    return;
  }

  if (index < text.length) {
    target.textContent += text.charAt(index);
    index += 1;
    setTimeout(typeWriter, 100);
  } else {
    setTimeout(() => {
      target.textContent = "";
      index = 0;
      typeWriter();
    }, 1200);
  }
}

function syncHeaderState() {
  if (!siteHeader) {
    return;
  }

  siteHeader.classList.toggle("is-scrolled", window.scrollY > 18);
}

function activateTestimonyTab(targetId) {
  testimonyTabs.forEach((tab) => {
    const isActive = tab.dataset.target === targetId;
    tab.classList.toggle("is-active", isActive);
    tab.setAttribute("aria-selected", String(isActive));
  });

  testimonyPanels.forEach((panel) => {
    const isActive = panel.id === targetId;
    panel.classList.toggle("is-active", isActive);
    panel.hidden = !isActive;
  });
}

function activateTopicBrowserTab(targetId) {
  topicBrowserTabs.forEach((tab) => {
    const isActive = tab.dataset.topicTarget === targetId;
    tab.classList.toggle("is-active", isActive);
    tab.setAttribute("aria-selected", String(isActive));
  });

  topicBrowserPanels.forEach((panel) => {
    const isActive = panel.id === targetId;
    panel.classList.toggle("is-active", isActive);
    panel.hidden = !isActive;
  });
}

function setHeaderMenuState(isOpen) {
  if (!headerMenu || !headerMenuToggle) {
    return;
  }

  headerMenu.classList.toggle("is-open", isOpen);
  headerMenuToggle.classList.toggle("is-open", isOpen);
  headerMenuToggle.setAttribute("aria-expanded", String(isOpen));
  headerMenuToggle.setAttribute("aria-label", isOpen ? "Close menu" : "Open menu");
}

function closeHeaderMenuOnDesktop() {
  if (window.innerWidth > 1024) {
    setHeaderMenuState(false);
  }
}

function activateChapterTopic(topic) {
  if (!chapterTopicFilter) {
    return;
  }

  const buttons = chapterTopicFilter.querySelectorAll("[data-topic-filter]");
  const items = document.querySelectorAll("[data-chapter-topic-results] [data-topic-item]");

  buttons.forEach((button) => {
    const isActive = button.dataset.topicFilter === topic;
    button.classList.toggle("is-active", isActive);
    button.setAttribute("aria-pressed", String(isActive));
  });

  items.forEach((item) => {
    const itemTopic = item.dataset.topicItem;
    const isVisible = topic === "all" || itemTopic === topic;
    item.hidden = !isVisible;
  });
}

window.addEventListener("scroll", syncHeaderState, { passive: true });
window.addEventListener("resize", closeHeaderMenuOnDesktop);

testimonyTabs.forEach((tab) => {
  tab.addEventListener("click", () => {
    activateTestimonyTab(tab.dataset.target);
  });
});

topicBrowserTabs.forEach((tab) => {
  tab.addEventListener("click", () => {
    activateTopicBrowserTab(tab.dataset.topicTarget);
  });
});

if (headerMenuToggle) {
  headerMenuToggle.addEventListener("click", () => {
    const isOpen = headerMenuToggle.getAttribute("aria-expanded") === "true";
    setHeaderMenuState(!isOpen);
  });
}

if (headerMenu) {
  headerMenu.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      setHeaderMenuState(false);
    });
  });
}

if (chapterTopicFilter) {
  chapterTopicFilter.querySelectorAll("[data-topic-filter]").forEach((button) => {
    button.addEventListener("click", () => {
      activateChapterTopic(button.dataset.topicFilter);
    });
  });
}

syncHeaderState();
closeHeaderMenuOnDesktop();
activateTopicBrowserTab("new-topic-panel");
activateChapterTopic("all");
setTimeout(typeWriter, 500);
