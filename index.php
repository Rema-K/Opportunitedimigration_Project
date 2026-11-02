<?php
declare(strict_types=1);
session_start();
$success = '';
$error = '';
$language = ($_POST['language'] ?? $_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr';
$labels = [
 'fr' => [
  'tag'=>'Accompagnement en mobilité internationale','home'=>'Accueil','destinations'=>'Destinations','services'=>'Services','map'=>'Explorer la carte','enquiry'=>'Faire une demande',
  'badge'=>'Des projets internationaux, un accompagnement humain','title'=>'Votre prochaine étape commence ici.','intro'=>'Présentez votre projet de voyage, d’études, de travail ou de mobilité internationale. Notre équipe vous orientera vers des informations utiles.',
  'start'=>'Parler de mon projet','explore'=>'Découvrir les destinations','note'=>'Information et accompagnement uniquement. Les décisions de visa appartiennent aux autorités compétentes.',
  'destTitle'=>'Destinations','destIntro'=>'Choisissez une destination pour préparer votre demande.','servicesTitle'=>'Nos services','servicesIntro'=>'Un accompagnement général adapté à vos objectifs.',
  'mapTitle'=>'Explorer la carte','mapIntro'=>'Choisissez un pays pour ouvrir sa recherche dans Google Maps.','choose'=>'Choisir une destination','openMap'=>'Ouvrir Google Maps',
  'formTitle'=>'Demande d’accompagnement','formIntro'=>'Remplissez ce formulaire. Votre demande sera enregistrée dans la base de données.',
  'name'=>'Nom complet','email'=>'Adresse email','phone'=>'Téléphone / WhatsApp','destination'=>'Destination souhaitée','project'=>'Type de projet','message'=>'Comment pouvons-nous vous aider ?','consent'=>'J’accepte que mes informations soient utilisées pour répondre à ma demande.','send'=>'Envoyer la demande',
  'study'=>'Études','work'=>'Travail','travel'=>'Voyage / tourisme','immigration'=>'Mobilité / immigration','other'=>'Autre demande','required'=>'Veuillez remplir tous les champs obligatoires et accepter le consentement.',
  'success'=>'Votre demande a été enregistrée. Merci !','failure'=>'La demande n’a pas pu être enregistrée. Vérifiez la connexion à la base de données ou réessayez plus tard.',
  'footer'=>'Opportunitedimigration fournit de l’information et de l’accompagnement. Aucun visa ni résultat n’est garanti. Vérifiez toujours les règles sur les sites officiels.',
  'menu'=>'Ouvrir le menu','close'=>'Fermer le menu','privacy'=>'Confidentialité'
 ],
 'en' => [
  'tag'=>'International mobility assistance','home'=>'Home','destinations'=>'Destinations','services'=>'Services','map'=>'Explore map','enquiry'=>'Make an enquiry',
  'badge'=>'International ambitions, personal guidance','title'=>'Your next chapter starts here.','intro'=>'Tell us about your travel, study, work or international mobility plans. Our team will guide you toward useful information.',
  'start'=>'Discuss my plans','explore'=>'Explore destinations','note'=>'Information and guidance only. Visa decisions are made by the relevant authorities.',
  'destTitle'=>'Destinations','destIntro'=>'Choose a destination to prepare your enquiry.','servicesTitle'=>'Our services','servicesIntro'=>'General guidance tailored to your goals.',
  'mapTitle'=>'Explore the map','mapIntro'=>'Choose a country to open its Google Maps search.','choose'=>'Choose a destination','openMap'=>'Open Google Maps',
  'formTitle'=>'Request assistance','formIntro'=>'Complete this form. Your enquiry will be saved in the database.',
  'name'=>'Full name','email'=>'Email address','phone'=>'Phone / WhatsApp','destination'=>'Preferred destination','project'=>'Project type','message'=>'How can we help?','consent'=>'I agree that my information may be used to respond to my enquiry.','send'=>'Submit enquiry',
  'study'=>'Study','work'=>'Work','travel'=>'Travel / tourism','immigration'=>'Mobility / immigration','other'=>'Other enquiry','required'=>'Complete the required fields and accept the consent statement.',
  'success'=>'Your enquiry has been saved. Thank you!','failure'=>'The enquiry could not be saved. Check the database connection or try again later.',
  'footer'=>'Opportunitedimigration provides information and assistance. No visa or outcome is guaranteed. Always check official websites for current rules.',
  'menu'=>'Open menu','close'=>'Close menu','privacy'=>'Privacy'
 ]
];
$t = $labels[$language];
$countries = [
 'Canada'=>'Canada','Italy'=>'Italie','Luxembourg'=>'Luxembourg','Germany'=>'Allemagne','France'=>'France',
 'United States'=>'États-Unis','United Kingdom'=>'Royaume-Uni','Switzerland'=>'Suisse','China'=>'Chine','Turkey'=>'Turquie'
];
$services = $language === 'fr'
 ? [['Conseils sur les démarches','Informations générales pour comprendre les étapes et consulter les sources officielles.'],['Études et orientation','Aide à organiser un projet d’études et à identifier les informations utiles.'],['Suivi et communication','Un point de contact pour échanger au sujet de votre demande.']]
 : [['Application guidance','General information about steps and official sources.'],['Study planning','Help organising study plans and finding useful information.'],['Follow-up and communication','A point of contact to discuss your enquiry.']];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_enquiry'])) {
    $name = trim((string)($_POST['full_name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $destination = trim((string)($_POST['destination'] ?? ''));
    $project = trim((string)($_POST['project_type'] ?? ''));
    $message = trim((string)($_POST['message'] ?? ''));
    $consent = isset($_POST['consent']);
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $phone === '' || !isset($countries[$destination]) || !in_array($project, ['study','work','travel','immigration','other'], true) || !$consent) {
        $error = $t['required'];
    } else {
        try {
            require __DIR__ . '/config.php';
            $stmt = $pdo->prepare('INSERT INTO enquiries (full_name, email, phone, destination, project_type, message, consent_given, preferred_language) VALUES (:full_name, :email, :phone, :destination, :project_type, :message, 1, :preferred_language)');
            $stmt->execute([
                ':full_name'=>$name, ':email'=>$email, ':phone'=>$phone, ':destination'=>$destination,
                ':project_type'=>$project, ':message'=>$message, ':preferred_language'=>$language
            ]);
            $success = $t['success'];
            $_POST = [];
        } catch (Throwable $e) {
            error_log('Opportunitedimigration enquiry save failed: '.$e->getMessage());
            $error = $t['failure'];
        }
    }
}
function e(string $value): string { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="<?= e($language) ?>">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Opportunitedimigration — travel, study, work and international mobility guidance.">
<meta name="theme-color" content="#0a192f"><title>Opportunite d'imigration | International mobility</title>
<style>
:root{--navy:#0a192f;--blue:#075985;--sky:#0284c7;--gold:#c9a44c;--paper:#f5f8fc;--ink:#182438}
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;font-family:Arial,Helvetica,sans-serif;background:var(--paper);color:var(--ink);line-height:1.6}
a{color:inherit}.top{background:var(--navy);color:#dbeafe;padding:8px 5%;display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;font-size:.85rem}
.top a{color:#dbeafe}.language a{display:inline-block;padding:2px 9px;border-radius:20px;text-decoration:none;border:1px solid #52627a;margin-left:4px}
header{position:sticky;top:0;z-index:5;background:#ffffffed;backdrop-filter:blur(12px);box-shadow:0 3px 16px #0a192f12}
.navbar{max-width:1180px;margin:auto;padding:14px 22px;display:flex;align-items:center;justify-content:space-between;gap:18px}
.brand{display:flex;gap:12px;align-items:center;text-decoration:none;font-weight:800;font-size:1.1rem;color:var(--navy)}
.logo{width:160px;height:75px;flex:0px 0px 45px;display:grid;place-items:center;border-radius:14px;color:white;font-size:1.4rem}
.logo img{display:block;width:100%;height:auto;max-height:100%;object-fit:contain}
.menu-btn{border:0;background:var(--navy);color:white;padding:11px 15px;border-radius:12px;font-size:1rem;cursor:pointer}
.menu-panel{display:none;position:absolute;right:max(22px,calc((100% - 1136px)/2));top:72px;width:min(330px,calc(100vw - 32px));padding:12px;background:white;border:1px solid #dce5ef;border-radius:18px;box-shadow:0 18px 45px #0a192f26}
.menu-panel.open{display:block}.menu-panel button{width:100%;text-align:left;padding:13px 14px;border:0;border-radius:10px;background:white;cursor:pointer;color:var(--navy);font-weight:700;font-size:1rem}
.menu-panel button:hover,.menu-panel button:focus{background:#eaf5ff;outline:2px solid #b9e3ff}
main{max-width:1180px;margin:auto;padding:28px 22px 60px}.view{display:none;animation:fade .2s ease}.view.active{display:block}
.hero{background:linear-gradient(135deg,#0a192f,#0c3555 55%,#0369a1);color:white;border-radius:28px;padding:clamp(28px,6vw,76px);min-height:430px;display:flex;align-items:center}
.hero-inner{max-width:760px}.badge{display:inline-block;background:#ffffff18;border:1px solid #ffffff35;border-radius:30px;padding:7px 13px;font-size:.9rem;color:#dbeafe}
h1{font-size:clamp(2.2rem,5vw,4.2rem);line-height:1.08;margin:22px 0}h2{font-size:clamp(1.8rem,4vw,2.5rem);color:var(--navy);margin:8px 0}
p{color:inherit}.hero p{font-size:1.1rem;color:#e2e8f0;max-width:700px}.actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:25px}
.btn{display:inline-block;border:0;border-radius:12px;padding:12px 17px;font-weight:800;text-decoration:none;cursor:pointer;background:var(--sky);color:white}
.btn.light{background:white;color:var(--navy)}.btn.outline{background:transparent;border:1px solid #ffffff77;color:white}
.gold-line{height:3px;max-width:430px;margin-top:30px;background:linear-gradient(90deg,var(--gold),#f3e4b5,var(--gold))}
.lead{color:#5b687a;margin-bottom:24px}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:16px}
.card{background:white;border:1px solid #e0e8f1;border-radius:18px;padding:20px;box-shadow:0 5px 18px #0a192f08}
.card h3{margin:0 0 8px;color:var(--navy)}.card p{color:#5b687a;font-size:.95rem}.card button{margin-top:8px}
form{background:white;border:1px solid #e0e8f1;border-radius:20px;padding:clamp(18px,4vw,34px);max-width:850px}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}.field{display:flex;flex-direction:column;gap:6px}.field.full{grid-column:1/-1}label{font-size:.88rem;font-weight:800;color:#35445a}
input,select,textarea{width:100%;border:1px solid #cbd5e1;border-radius:10px;padding:12px;font:inherit;background:white;color:var(--ink)}input:focus,select:focus,textarea:focus{outline:3px solid #bae6fd;border-color:var(--sky)}
.consent{display:flex;gap:10px;align-items:flex-start;margin:18px 0;font-size:.9rem}.consent input{width:auto;margin-top:6px}
.alert{padding:13px 16px;border-radius:12px;margin:0 0 18px}.ok{background:#dcfce7;color:#166534}.err{background:#fee2e2;color:#991b1b}
.map-box{border:0;width:100%;height:450px;border-radius:18px;background:#dbeafe}.map-select{max-width:400px;margin:12px 0 18px}
footer{background:#071426;color:#cbd5e1;padding:28px 5%;text-align:center;font-size:.9rem}footer a{color:#bfdbfe}
@keyframes fade{from{opacity:.3;transform:translateY(4px)}to{opacity:1;transform:none}}
@media(max-width:650px){.top{padding:8px 16px}.navbar{padding:12px 16px}.brand{font-size:.95rem}.logo{width:40px;height:40px;flex-basis:40px}main{padding:18px 14px 42px}.hero{border-radius:20px;min-height:0}.form-grid{grid-template-columns:1fr}.field.full{grid-column:auto}.map-box{height:330px}.menu-panel{right:14px;top:66px}}
@media(prefers-reduced-motion:reduce){html{scroll-behavior:auto}.view{animation:none}}
</style>
</head>
<body>
<div class="top"><span><?= e($t['tag']) ?></span><span><a href="mailto:Opportunitedimigration@gmail.com">Opportunitedimigration@gmail.com</a> &nbsp;|&nbsp; <span class="language"><a href="?lang=fr" lang="fr">FR</a><a href="?lang=en" lang="en">EN</a></span></span></div>
<header><div class="navbar"><a class="brand" href="#" onclick="showView('home');return false"><span class="logo"><img src="ChatGPT_Image_Nov_2__2026__09_17_52_PM-removebg-preview.png" alt="Opportunitedimigration" width="612" height="408"></span></a>
<button class="menu-btn" id="menuBtn" type="button" aria-expanded="false" aria-controls="menuPanel" onclick="toggleMenu()">☰ &nbsp; <?= e($t['menu']) ?></button>
<nav class="menu-panel" id="menuPanel" aria-label="Main menu">
<button type="button" onclick="navigate('home')">⌂ &nbsp; <?= e($t['home']) ?></button>
<button type="button" onclick="navigate('destinations')">◎ &nbsp; <?= e($t['destinations']) ?></button>
<button type="button" onclick="navigate('services')">✧ &nbsp; <?= e($t['services']) ?></button>
<button type="button" onclick="navigate('map')">⌖ &nbsp; <?= e($t['map']) ?></button>
<button type="button" onclick="navigate('enquiry')">✉ &nbsp; <?= e($t['enquiry']) ?></button>
</nav></div></header>
<main>
<section class="view active" id="view-home"><div class="hero"><div class="hero-inner"><span class="badge"><?= e($t['badge']) ?></span><h1><?= e($t['title']) ?></h1><p><?= e($t['intro']) ?></p><div class="actions"><button class="btn light" onclick="navigate('enquiry')"><?= e($t['start']) ?> →</button><button class="btn outline" onclick="navigate('destinations')"><?= e($t['explore']) ?></button></div><div class="gold-line"></div><p style="font-size:.82rem"><?= e($t['note']) ?></p></div></div></section>
<section class="view" id="view-destinations"><h2><?= e($t['destTitle']) ?></h2><p class="lead"><?= e($t['destIntro']) ?></p><div class="grid">
<?php foreach ($countries as $english=>$french): ?><article class="card"><h3><?= e($language==='fr'?$french:$english) ?></h3><p><?= e($language==='fr'?'Informations générales sur les études, le travail, les voyages et la mobilité.':'General information about study, work, travel and mobility.') ?></p><button class="btn" onclick="setDestination('<?= e($english) ?>')"><?= e($t['choose']) ?></button></article><?php endforeach; ?>
</div></section>
<section class="view" id="view-services"><h2><?= e($t['servicesTitle']) ?></h2><p class="lead"><?= e($t['servicesIntro']) ?></p><div class="grid"><?php foreach($services as $service): ?><article class="card"><h3><?= e($service[0]) ?></h3><p><?= e($service[1]) ?></p></article><?php endforeach; ?></div></section>
<section class="view" id="view-map"><h2><?= e($t['mapTitle']) ?></h2><p class="lead"><?= e($t['mapIntro']) ?></p><label for="mapCountry"><?= e($t['destination']) ?></label><select id="mapCountry" class="map-select" onchange="updateMap()"><?php foreach($countries as $english=>$french): ?><option value="<?= e($english) ?>"><?= e($language==='fr'?$french:$english) ?></option><?php endforeach; ?></select><iframe id="mapFrame" class="map-box" title="Google Maps" loading="lazy" src="https://maps.google.com/maps?q=Canada&output=embed"></iframe><p><a class="btn" id="openMap" href="https://www.google.com/maps/search/?api=1&query=Canada" target="_blank" rel="noopener noreferrer"><?= e($t['openMap']) ?></a></p></section>
<section class="view" id="view-enquiry"><h2><?= e($t['formTitle']) ?></h2><p class="lead"><?= e($t['formIntro']) ?></p>
<?php if($success): ?><div class="alert ok" role="status"><?= e($success) ?></div><?php endif; ?><?php if($error): ?><div class="alert err" role="alert"><?= e($error) ?></div><?php endif; ?>
<form method="post" action="index.php#enquiry" onsubmit="return validateConsent()"><input type="hidden" name="language" value="<?= e($language) ?>"><div class="form-grid">
<div class="field"><label for="full_name"><?= e($t['name']) ?> *</label><input id="full_name" name="full_name" maxlength="120" required value="<?= e((string)($_POST['full_name']??'')) ?>"></div>
<div class="field"><label for="email"><?= e($t['email']) ?> *</label><input id="email" type="email" name="email" maxlength="254" required value="<?= e((string)($_POST['email']??'')) ?>"></div>
<div class="field"><label for="phone"><?= e($t['phone']) ?> *</label><input id="phone" type="tel" name="phone" maxlength="40" required value="<?= e((string)($_POST['phone']??'')) ?>"></div>
<div class="field"><label for="destination"><?= e($t['destination']) ?> *</label><select id="destination" name="destination" required><?php foreach($countries as $english=>$french): ?><option value="<?= e($english) ?>" <?= (($_POST['destination']??'Canada')===$english?'selected':'') ?>><?= e($language==='fr'?$french:$english) ?></option><?php endforeach; ?></select></div>
<div class="field full"><label for="project_type"><?= e($t['project']) ?> *</label><select id="project_type" name="project_type" required><?php foreach(['study'=>'study','work'=>'work','travel'=>'travel','immigration'=>'immigration','other'=>'other'] as $value=>$key): ?><option value="<?= e($value) ?>" <?= (($_POST['project_type']??'')===$value?'selected':'') ?>><?= e($t[$key]) ?></option><?php endforeach; ?></select></div>
<div class="field full"><label for="message"><?= e($t['message']) ?></label><textarea id="message" name="message" rows="4" maxlength="3000"><?= e((string)($_POST['message']??'')) ?></textarea></div>
</div><label class="consent"><input type="checkbox" name="consent" required><span><?= e($t['consent']) ?> <?= e($language==='fr'?'N’envoyez pas de passeport ou de documents sensibles via ce formulaire.':'Do not send passports or sensitive documents through this form.') ?></span></label><button class="btn" type="submit" name="submit_enquiry" value="1"><?= e($t['send']) ?></button></form></section>
</main>
<footer><strong>Opportunitedimigration</strong><p><?= e($t['footer']) ?></p><p><a href="mailto:Opportunitedimigration@gmail.com">Opportunitedimigration@gmail.com</a></p><small>© <?= date('Y') ?> Opportunitedimigration</small></footer>
<script>
function toggleMenu(){const panel=document.getElementById('menuPanel'),btn=document.getElementById('menuBtn');const open=panel.classList.toggle('open');btn.setAttribute('aria-expanded',String(open));}
function closeMenu(){document.getElementById('menuPanel').classList.remove('open');document.getElementById('menuBtn').setAttribute('aria-expanded','false');}
function showView(name){document.querySelectorAll('.view').forEach(v=>v.classList.remove('active'));const view=document.getElementById('view-'+name);if(view)view.classList.add('active');closeMenu();window.scrollTo({top:0,behavior:'smooth'});if(name==='map')updateMap();}
function navigate(name){showView(name);if(name==='enquiry')history.replaceState(null,'','#enquiry');else history.replaceState(null,'','#'+name);}
function setDestination(country){document.getElementById('destination').value=country;navigate('enquiry');}
function updateMap(){const country=document.getElementById('mapCountry').value;document.getElementById('mapFrame').src='https://maps.google.com/maps?q='+encodeURIComponent(country)+'&output=embed';document.getElementById('openMap').href='https://www.google.com/maps/search/?api=1&query='+encodeURIComponent(country);}
function validateConsent(){return true;}
document.addEventListener('click',function(e){const panel=document.getElementById('menuPanel'),btn=document.getElementById('menuBtn');if(panel.classList.contains('open')&&!panel.contains(e.target)&&!btn.contains(e.target))closeMenu();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeMenu();});
if(location.hash==='#enquiry')showView('enquiry');
</script>
</body></html>
