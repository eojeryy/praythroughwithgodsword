const siteHeader = document.getElementById("site-header");
const headerMenu = document.getElementById("header-menu");
const headerMenuToggle = document.getElementById("header-menu-toggle");
const testimonyTabs = document.querySelectorAll(".testimony-tab");
const testimonyPanels = document.querySelectorAll(".testimony-panel");
const topicBrowserTabs = document.querySelectorAll(".topic-browser-tab");
const topicBrowserPanels = document.querySelectorAll(".topic-browser-panel");
const chapterTopicFilter = document.querySelector("[data-chapter-topic-filter]");

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
    tab.tabIndex = isActive ? 0 : -1;
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
    tab.tabIndex = isActive ? 0 : -1;
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
  const breakpoint = document.body.classList.contains("home-page") ? 1280 : 1024;
  if (window.innerWidth > breakpoint) {
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


// Let keyboard users move between tabs without tabbing through every tab.
function enableTabKeyboard(tabs, panels, activate, targetAttribute) {
  tabs.forEach((tab, position) => {
    tab.id = tab.id || `${targetAttribute}-tab-${position}`;
    const panel = Array.from(panels).find((item) => item.id === tab.dataset[targetAttribute]);
    if (panel) panel.setAttribute("aria-labelledby", tab.id);
    tab.addEventListener("keydown", (event) => {
      let next = position;
      if (event.key === "ArrowRight") next = (position + 1) % tabs.length;
      else if (event.key === "ArrowLeft") next = (position - 1 + tabs.length) % tabs.length;
      else if (event.key === "Home") next = 0;
      else if (event.key === "End") next = tabs.length - 1;
      else return;
      event.preventDefault();
      activate(tabs[next].dataset[targetAttribute]);
      tabs[next].focus();
    });
  });
}

enableTabKeyboard(testimonyTabs, testimonyPanels, activateTestimonyTab, "target");
enableTabKeyboard(topicBrowserTabs, topicBrowserPanels, activateTopicBrowserTab, "topicTarget");
activateTestimonyTab("new-testimony-panel");

const homeSearch = document.querySelector("[data-home-search]");
if (homeSearch) {
  const results = document.querySelector("[data-home-results]");
  const list = document.querySelector("[data-home-search-list]");
  const status = document.querySelector("[data-home-search-status]");
  const empty = document.querySelector("[data-home-search-empty]");
  const clear = document.querySelector("[data-home-search-clear]");
  const library = Array.from(document.querySelectorAll("[data-home-search-item]"));

  function searchHomeLibrary(updateAddress = false) {
    const query = homeSearch.value.trim();
    const words = query.toLocaleLowerCase().split(/\s+/).filter(Boolean);
    const matches = words.length ? library.filter((item) => {
      const searchable = `${item.dataset.searchTitle} ${item.textContent}`.toLocaleLowerCase();
      return words.every((word) => searchable.includes(word));
    }) : [];
    results.hidden = !query;
    list.replaceChildren();
    matches.forEach((item) => {
      const link = document.createElement("a");
      link.href = item.href;
      link.className = "home-search-result";
      const kind = document.createElement("small");
      kind.textContent = item.dataset.searchKind;
      const title = document.createElement("strong");
      title.textContent = item.dataset.searchTitle;
      link.append(kind, title);
      list.append(link);
    });
    status.textContent = `${matches.length} result${matches.length === 1 ? "" : "s"} for “${query}”`;
    empty.hidden = matches.length > 0;
    if (updateAddress) {
      const url = new URL(window.location.href);
      if (query) url.searchParams.set("q", query);
      else url.searchParams.delete("q");
      window.history.replaceState(null, "", url);
    }
  }

  homeSearch.addEventListener("input", () => searchHomeLibrary(true));
  homeSearch.form.addEventListener("submit", (event) => {
    event.preventDefault();
    searchHomeLibrary(true);
  });
  clear.addEventListener("click", () => {
    homeSearch.value = "";
    searchHomeLibrary(true);
    homeSearch.focus();
  });
  searchHomeLibrary();
}
