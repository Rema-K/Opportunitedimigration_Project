// Client-side version of the former index.php (Netlify cannot run PHP).
const LABELS = {
 "fr": {
  "tag": "Accompagnement en mobilité internationale",
  "home": "Accueil",
  "destinations": "Destinations",
  "services": "Services",
  "map": "Explorer la carte",
  "enquiry": "Faire une demande",
  "badge": "Des projets internationaux, un accompagnement humain",
  "title": "Votre prochaine étape commence ici.",
  "intro": "Présentez votre projet de voyage, d’études, de travail ou de mobilité internationale. Notre équipe vous orientera vers des informations utiles.",
  "start": "Parler de mon projet",
  "explore": "Découvrir les destinations",
  "note": "Information et accompagnement uniquement. Les décisions de visa appartiennent aux autorités compétentes.",
  "destTitle": "Destinations",
  "destIntro": "Choisissez une destination pour préparer votre demande.",
  "servicesTitle": "Nos services",
  "servicesIntro": "Un accompagnement général adapté à vos objectifs.",
  "mapTitle": "Explorer la carte",
  "mapIntro": "Choisissez un pays pour ouvrir sa recherche dans Google Maps.",
  "choose": "Choisir une destination",
  "openMap": "Ouvrir Google Maps",
  "formTitle": "Demande d’accompagnement",
  "formIntro": "Remplissez ce formulaire. Votre demande sera enregistrée dans la base de données.",
  "name": "Nom complet",
  "email": "Adresse email",
  "phone": "Téléphone / WhatsApp",
  "destination": "Destination souhaitée",
  "project": "Type de projet",
  "message": "Comment pouvons-nous vous aider ?",
  "consent": "J’accepte que mes informations soient utilisées pour répondre à ma demande.",
  "send": "Envoyer la demande",
  "study": "Études",
  "work": "Travail",
  "travel": "Voyage / tourisme",
  "immigration": "Mobilité / immigration",
  "other": "Autre demande",
  "required": "Veuillez remplir tous les champs obligatoires et accepter le consentement.",
  "success": "Votre demande a été enregistrée. Merci !",
  "failure": "La demande n’a pas pu être enregistrée. Vérifiez la connexion à la base de données ou réessayez plus tard.",
  "footer": "Opportunitedimigration fournit de l’information et de l’accompagnement. Aucun visa ni résultat n’est garanti. Vérifiez toujours les règles sur les sites officiels.",
  "menu": "Ouvrir le menu",
  "close": "Fermer le menu",
  "privacy": "Confidentialité",
  "sensitive": "N’envoyez pas de passeport ou de documents sensibles via ce formulaire.",
  "destCard": "Informations générales sur les études, le travail, les voyages et la mobilité.",
  "sending": "Envoi…"
 },
 "en": {
  "tag": "International mobility assistance",
  "home": "Home",
  "destinations": "Destinations",
  "services": "Services",
  "map": "Explore map",
  "enquiry": "Make an enquiry",
  "badge": "International ambitions, personal guidance",
  "title": "Your next chapter starts here.",
  "intro": "Tell us about your travel, study, work or international mobility plans. Our team will guide you toward useful information.",
  "start": "Discuss my plans",
  "explore": "Explore destinations",
  "note": "Information and guidance only. Visa decisions are made by the relevant authorities.",
  "destTitle": "Destinations",
  "destIntro": "Choose a destination to prepare your enquiry.",
  "servicesTitle": "Our services",
  "servicesIntro": "General guidance tailored to your goals.",
  "mapTitle": "Explore the map",
  "mapIntro": "Choose a country to open its Google Maps search.",
  "choose": "Choose a destination",
  "openMap": "Open Google Maps",
  "formTitle": "Request assistance",
  "formIntro": "Complete this form. Your enquiry will be saved in the database.",
  "name": "Full name",
  "email": "Email address",
  "phone": "Phone / WhatsApp",
  "destination": "Preferred destination",
  "project": "Project type",
  "message": "How can we help?",
  "consent": "I agree that my information may be used to respond to my enquiry.",
  "send": "Submit enquiry",
  "study": "Study",
  "work": "Work",
  "travel": "Travel / tourism",
  "immigration": "Mobility / immigration",
  "other": "Other enquiry",
  "required": "Complete the required fields and accept the consent statement.",
  "success": "Your enquiry has been saved. Thank you!",
  "failure": "The enquiry could not be saved. Check the database connection or try again later.",
  "footer": "Opportunitedimigration provides information and assistance. No visa or outcome is guaranteed. Always check official websites for current rules.",
  "menu": "Open menu",
  "close": "Close menu",
  "privacy": "Privacy",
  "sensitive": "Do not send passports or sensitive documents through this form.",
  "destCard": "General information about study, work, travel and mobility.",
  "sending": "Sending…"
 }
};
const COUNTRIES = {"Canada":"Canada","Italy":"Italie","Luxembourg":"Luxembourg","Germany":"Allemagne","France":"France","United States":"États-Unis","United Kingdom":"Royaume-Uni","Switzerland":"Suisse","China":"Chine","Turkey":"Turquie"};
const SERVICES = {
 fr: [["Conseils sur les démarches","Informations générales pour comprendre les étapes et consulter les sources officielles."],["Études et orientation","Aide à organiser un projet d’études et à identifier les informations utiles."],["Suivi et communication","Un point de contact pour échanger au sujet de votre demande."]],
 en: [["Application guidance","General information about steps and official sources."],["Study planning","Help organising study plans and finding useful information."],["Follow-up and communication","A point of contact to discuss your enquiry."]]
};
const PROJECTS = ["study","work","travel","immigration","other"];

let language = new URLSearchParams(location.search).get("lang") === "en" ? "en" : (localStorage.getItem("lang") === "en" ? "en" : "fr");
const t = (k) => LABELS[language][k] || k;
const $ = (id) => document.getElementById(id);
const esc = (s) => String(s).replace(/[&<>"']/g, (c) => ({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#39;"}[c]));
const countryName = (en) => (language === "fr" ? COUNTRIES[en] : en);

function fillSelect(sel, items, keep) {
  const current = sel.value || keep;
  sel.innerHTML = items.map(([v, label]) => `<option value="${esc(v)}">${esc(label)}</option>`).join("");
  if (current) sel.value = current;
}

function render() {
  document.documentElement.lang = language;
  document.querySelectorAll("[data-i18n]").forEach((el) => { el.textContent = t(el.dataset.i18n); });
  $("menuBtn").setAttribute("aria-label", t("menu"));
  $("destGrid").innerHTML = Object.keys(COUNTRIES).map((c) =>
    `<article class="card"><h3>${esc(countryName(c))}</h3><p>${esc(t("destCard"))}</p><button class="btn" type="button" data-country="${esc(c)}">${esc(t("choose"))}</button></article>`
  ).join("");
  $("servicesGrid").innerHTML = SERVICES[language].map(([h, p]) => `<article class="card"><h3>${esc(h)}</h3><p>${esc(p)}</p></article>`).join("");
  const countries = Object.keys(COUNTRIES).map((c) => [c, countryName(c)]);
  fillSelect($("mapCountry"), countries, "Canada");
  fillSelect($("destination"), countries, "Canada");
  fillSelect($("project_type"), PROJECTS.map((p) => [p, t(p)]), "study");
}

function setLanguage(lang) {
  language = lang === "en" ? "en" : "fr";
  localStorage.setItem("lang", language);
  const url = new URL(location.href);
  url.searchParams.set("lang", language);
  history.replaceState(null, "", url);
  render();
}

function toggleMenu(){const panel=$("menuPanel"),btn=$("menuBtn");const open=panel.classList.toggle("open");btn.setAttribute("aria-expanded",String(open));}
function closeMenu(){$("menuPanel").classList.remove("open");$("menuBtn").setAttribute("aria-expanded","false");}
function showView(name){document.querySelectorAll(".view").forEach(v=>v.classList.remove("active"));const view=$("view-"+name);if(view)view.classList.add("active");closeMenu();window.scrollTo({top:0,behavior:"smooth"});if(name==="map")updateMap();}
function navigate(name){showView(name);history.replaceState(null,"",location.pathname+location.search+"#"+name);}
function setDestination(country){$("destination").value=country;navigate("enquiry");}
function updateMap(){const country=$("mapCountry").value;$("mapFrame").src="https://maps.google.com/maps?q="+encodeURIComponent(country)+"&output=embed";$("openMap").href="https://www.google.com/maps/search/?api=1&query="+encodeURIComponent(country);}

function showAlert(kind, msg) {
  const a = $("formAlert");
  a.hidden = false;
  a.className = "alert " + (kind === "ok" ? "ok" : "err");
  a.setAttribute("role", kind === "ok" ? "status" : "alert");
  a.textContent = msg;
}

$("enquiryForm").addEventListener("submit", async (e) => {
  e.preventDefault();
  const form = e.target;
  const data = {
    full_name: form.full_name.value.trim(),
    email: form.email.value.trim(),
    phone: form.phone.value.trim(),
    destination: form.destination.value,
    project_type: form.project_type.value,
    message: form.message.value.trim(),
    consent: form.consent.checked,
    language,
  };
  if (!form.checkValidity() || !data.full_name || !data.phone || !data.consent) {
    showAlert("err", t("required"));
    return;
  }
  const btn = $("submitBtn");
  btn.disabled = true; btn.textContent = t("sending");
  try {
    const res = await fetch("/api/enquiries", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(data) });
    const json = await res.json().catch(() => ({}));
    if (res.ok && json.ok) {
      showAlert("ok", t("success"));
      form.reset();
      render();
    } else {
      showAlert("err", json.error === "validation" ? t("required") : t("failure"));
    }
  } catch {
    showAlert("err", t("failure"));
  } finally {
    btn.disabled = false; btn.textContent = t("send");
  }
});

$("destGrid").addEventListener("click", (e) => { const b = e.target.closest("[data-country]"); if (b) setDestination(b.dataset.country); });
document.addEventListener("click",function(e){const panel=$("menuPanel"),btn=$("menuBtn");if(panel.classList.contains("open")&&!panel.contains(e.target)&&!btn.contains(e.target))closeMenu();});
document.addEventListener("keydown",function(e){if(e.key==="Escape")closeMenu();});

$("year").textContent = new Date().getFullYear();
render();
const initial = location.hash.slice(1);
if (["destinations","services","map","enquiry"].includes(initial)) showView(initial);
