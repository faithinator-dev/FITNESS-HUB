const sitePages = {
  about: {
    title: "About Fitness Hub",
    eyebrow: "A healthier rhythm, one day at a time",
    lead: "Fitness Hub is here to make movement feel welcoming, practical, and part of everyday life.",
    content: `<div class="site-grid">
      <article class="site-card"><h2>Progress at your pace</h2><p>Start where you are and build a routine that fits your goals. Consistency matters more than having a perfect first day.</p></article>
      <article class="site-card"><h2>Guidance that helps</h2><p>Explore strength training, conditioning, and trainer support designed to help you move with confidence.</p></article>
      <article class="site-card"><h2>A place to keep going</h2><p>Use your member dashboard to view assigned routines and keep a simple record of gym visits.</p></article>
    </div><h2>Our approach</h2><p>We believe good fitness should feel achievable. The site brings together gym information, membership options, trainer profiles, and tools for members in one place.</p><p>Fitness Hub is currently presented as a demo. Membership selections on this site do not take payment or activate a paid subscription.</p><p><a class="site-button" href="programs.html">Explore programs</a></p>`,
  },
  programs: {
    title: "Programs",
    eyebrow: "Find your way to move",
    lead: "Choose a starting point and shape a routine around what you want to work on.",
    content: `<div class="site-grid">
      <article class="site-card"><h2>Strength training</h2><p>Build familiarity with resistance exercises and progress at a pace that feels manageable.</p></article>
      <article class="site-card"><h2>Conditioning</h2><p>Mix cardio and movement work to support stamina and everyday energy.</p></article>
      <article class="site-card"><h2>Trainer guidance</h2><p>Get help choosing exercises, learning technique, and keeping your training consistent.</p></article>
    </div><div class="site-callout">Program descriptions are for this project demo. Ask the gym team about current class times and availability.</div><p><a class="site-button" href="trainers.html">Meet the trainers</a></p>`,
  },
  trainers: {
    title: "Meet the trainers",
    eyebrow: "People who can help you get started",
    lead: "Explore the trainer profiles and find support for your next step.",
    content: `<div class="site-grid">
      <article class="site-card site-image-card"><img src="assets/images/trainer-1.jpg" alt="Fitness trainer"><div class="site-card-copy"><h2>Personal training</h2><p>Support and guidance to help you move with confidence.</p></div></article>
      <article class="site-card site-image-card"><img src="assets/images/trainer-2.jpg" alt="Michael Adams, strength coach"><div class="site-card-copy"><h2>Michael Adams</h2><p>Strength coach helping members build muscle and endurance with proven training methods.</p></div></article>
      <article class="site-card site-image-card"><img src="assets/images/trainer-3.jpg" alt="Fitness trainer"><div class="site-card-copy"><h2>Strength coaching</h2><p>Build a steady routine with coaching that meets you where you are.</p></div></article>
    </div><p>Only Michael Adams is identified by name in the current demo. Add each trainer’s name, qualifications, and availability before publishing these profiles as real staff information.</p><p><a class="site-button" href="contact.html">Ask about training</a></p>`,
  },
  pricing: {
    title: "Pricing",
    eyebrow: "Choose a plan to explore",
    lead: "Compare the demo membership options. Selecting a plan takes you to sign-in and carries your choice into the member dashboard.",
    content: `<div class="site-grid">
      <article class="site-card"><h2>Basic</h2><p class="site-price">$299</p><ul><li>Full gym equipment access</li><li>Locker and shower access</li><li>Flexible workout hours</li><li>Monthly progress tracking</li></ul><a class="site-button" href="login.html?plan=basic">Choose Basic</a></article>
      <article class="site-card"><h2>Standard</h2><p class="site-price">$399</p><ul><li>Full gym access</li><li>Group fitness class entry</li><li>One personal training session</li><li>Progress tracking</li></ul><a class="site-button" href="login.html?plan=standard">Choose Standard</a></article>
      <article class="site-card"><h2>Premium</h2><p class="site-price">$500</p><ul><li>Unlimited gym access</li><li>Group fitness classes</li><li>Trainer support</li><li>Nutrition guidance</li><li>Advanced progress tracking</li></ul><a class="site-button" href="login.html?plan=premium">Choose Premium</a></article>
    </div><div class="site-callout">Demo only: displayed prices have no billing period or currency configured, and choosing a plan does not charge you or create a paid membership.</div>`,
  },
  contact: {
    title: "Contact Fitness Hub",
    eyebrow: "We’re glad you reached out",
    lead: "Send a message in this demo form or use the member portal to explore the site.",
    content: `<div class="site-callout">The project does not yet have a configured public email address or message service. This form is a front-end demo and will not send or save your message.</div>
      <form class="site-form" id="contact-demo-form"><label>Your name<input name="name" autocomplete="name" required></label><label>Email address<input name="email" type="email" autocomplete="email" required></label><label>How can we help?<textarea name="message" required></textarea></label><button class="site-button" type="submit">Preview message</button><p class="site-form-message" id="contact-demo-message" aria-live="polite"></p></form>`,
  },
  faqs: {
    title: "Frequently asked questions",
    eyebrow: "A few helpful answers",
    lead: "Quick information about plans, accounts, and the Fitness Hub demo.",
    content: `<div class="site-faq">
      <details><summary>Does choosing a plan take payment?</summary><p>No. The plan flow is a demo. It remembers your selection and shows it on the member dashboard after sign-in; it does not collect payment.</p></details>
      <details><summary>How do I choose a membership plan?</summary><p>Open Pricing or Membership Plans, choose an option, then sign in or create a member account. Your selection continues to the dashboard.</p></details>
      <details><summary>What can members do in the dashboard?</summary><p>The demo dashboard shows the selected plan, workout routines, and attendance tools. Some tools need the local backend and database running.</p></details>
      <details><summary>Can I ask about a trainer or program?</summary><p>Use the Contact page to preview a message. The demo does not send or store it yet.</p></details>
      <details><summary>Where can I read the site policies?</summary><p>Visit <a href="terms.html">Terms &amp; Conditions</a>, <a href="privacy.html">Privacy Policy</a>, or <a href="cookies.html">Cookies Policy</a>. These are starter drafts for this demo.</p></details>
    </div>`,
  },
  "membership-plans": {
    title: "Membership plans",
    eyebrow: "A plan for your next step",
    lead: "Choose a demo option. Your selection will be carried through sign-in to your member dashboard.",
    content: `<div class="site-grid">
      <article class="site-card"><h2>Basic</h2><p class="site-price">$299</p><p>Gym equipment access, lockers and showers, flexible workout hours, and progress tracking.</p><a class="site-button" href="login.html?plan=basic">Continue with Basic</a></article>
      <article class="site-card"><h2>Standard</h2><p class="site-price">$399</p><p>Gym access, group class entry, one personal training session, and progress tracking.</p><a class="site-button" href="login.html?plan=standard">Continue with Standard</a></article>
      <article class="site-card"><h2>Premium</h2><p class="site-price">$500</p><p>Unlimited access, group classes, trainer support, nutrition guidance, and advanced tracking.</p><a class="site-button" href="login.html?plan=premium">Continue with Premium</a></article>
    </div><div class="site-callout">These are demo prices only. No billing period, payment provider, or paid membership activation is configured.</div>`,
  },
  terms: {
    title: "Terms & Conditions",
    eyebrow: "Starter draft · Demo website",
    lead: "These draft terms explain how to use the current Fitness Hub website demo.",
    content: `<div class="site-callout site-draft"><strong>Before launch:</strong> This is starter copy, not a reviewed contract. Add the gym’s legal name, location, contact details, membership rules, billing terms, cancellation process, and have the final terms reviewed for the places where you operate.</div>
      <h2>Using this website</h2><p>Use the site and its member tools lawfully and provide accurate account information. Do not attempt to disrupt the site, access another person’s account, or misuse its content.</p>
      <h2>Plans and payments</h2><p>Plan names and prices on this demo are examples. Selecting a plan only saves a demo choice in the browser and member dashboard. It does not take payment, reserve a place, or create a paid membership.</p>
      <h2>Accounts and availability</h2><p>You are responsible for keeping your sign-in details private. The demo may be unavailable while the local development server or database is offline. Do not rely on this demo for emergency, medical, or time-critical services.</p>
      <h2>Fitness information</h2><p>Workout and program information is general website content. Consider your own needs and seek qualified professional advice before starting exercise if you have health concerns.</p>
      <h2>Updates and contact</h2><p>These terms may need to change as the project develops. No operator contact details are configured yet; add them before publishing this page as a live policy.</p>`,
  },
  privacy: {
    title: "Privacy Policy",
    eyebrow: "Starter draft · Demo website",
    lead: "This page describes data handling visible in the current FitLife project code. Confirm the production setup before relying on it as a live privacy notice.",
    content: `<div class="site-callout site-draft"><strong>Before launch:</strong> This is a technical starter draft, not a compliance review. Confirm your organization’s identity, purposes, legal bases, retention periods, processors, security controls, and contact for privacy requests.</div>
      <h2>Information used by the demo</h2><p>Account registration asks for a name, email address, phone number, password, and account role. The backend stores account records and a password hash; sign-in creates an API token. Member tools may also process attendance and assigned workout information.</p>
      <h2>Browser storage</h2><p>The website stores the signed-in user, API token, and selected demo plan in browser local storage so the pages can keep the session and plan selection. Signing out removes the account and token; the demo plan is cleared by the login flow when no plan is selected.</p>
      <h2>Messages and payments</h2><p>The Contact page form is a front-end preview and does not send or save messages. Plan selection is a demo and does not take payment.</p>
      <h2>Retention and requests</h2><p>The project does not define a published retention schedule or privacy-request contact. Add those details and a working request process before using real member information.</p>
      <h2>More information</h2><p>For background, consult the <a href="https://placng.org/i/wp-content/uploads/2023/06/Nigeria-Data-Protection-Act-2023.pdf" target="_blank" rel="noopener">Nigeria Data Protection Act 2023</a> and the <a href="https://ndpc.gov.ng/" target="_blank" rel="noopener">Nigeria Data Protection Commission</a>. This page is not a statement that the demo has been assessed for compliance.</p>`,
  },
  cookies: {
    title: "Cookies Policy",
    eyebrow: "Browser storage in this demo",
    lead: "The current project uses browser local storage for session and demo-plan continuity.",
    content: `<h2>Cookies and local storage</h2><p>Cookies and browser local storage are different technologies. In the current website code, the app stores the signed-in user, API token, and selected demo plan in local storage. This lets the pages keep the demo session and remember the plan chosen before sign-in.</p>
      <h2>Managing stored data</h2><p>You can clear this site’s local storage in your browser settings. Signing out also removes the saved user and API token. Clearing browser storage may sign you out or remove the selected demo plan.</p>
      <h2>Future changes</h2><p>If analytics, advertising, or other storage technologies are added, update this page to describe them and provide any controls required for the intended audience and applicable law.</p>
      <p><a href="privacy.html">Read the Privacy Policy</a></p>`,
  },
  legal: {
    title: "Legal information",
    eyebrow: "Website notes",
    lead: "Important context for the current Fitness Hub project demo.",
    content: `<div class="site-callout site-draft">This demo does not yet identify the operating company, registered address, or official contact channel. Add those details before public launch.</div>
      <h2>Demo status</h2><p>Plan selection, the contact form, and some dashboard interactions are demonstrations. They do not create a paid membership, send a contact message, or guarantee live service availability.</p>
      <h2>Site content</h2><p>Gym, trainer, and program information is presented for the project. Confirm names, qualifications, prices, availability, and permissions for all images before publishing commercially.</p>
      <h2>Related policies</h2><p>Read the <a href="terms.html">Terms &amp; Conditions</a>, <a href="privacy.html">Privacy Policy</a>, and <a href="cookies.html">Cookies Policy</a>.</p>`,
  },
};

const pageRoot = document.getElementById("site-page");
const pageKey = pageRoot?.dataset.page;
const page = sitePages[pageKey] || sitePages.about;
document.title = `${page.title} | Fitness Hub`;
document.body.classList.add("site-page");

const logo = `<a class="site-brand" href="index.html#home" aria-label="Fitness Hub home"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round"><path d="M6 5v14M18 5v14M2 9v6M22 9v6M6 12h12"/></svg><span>FITNESS HUB</span></a>`;
const nav = [
  ["Home", "index.html#home", "home"],
  ["About", "about.html", "about"],
  ["Programs", "programs.html", "programs"],
  ["Trainers", "trainers.html", "trainers"],
  ["Contact", "contact.html", "contact"],
];

pageRoot.innerHTML = `
  <header class="site-header"><div class="site-nav">${logo}<nav class="site-nav-links" aria-label="Main navigation">${nav.map(([label, href, key]) => `<a href="${href}"${pageKey === key ? ' aria-current="page"' : ""}>${label}</a>`).join("")}</nav><a class="site-join" href="login.html">Member sign in</a></div></header>
  <main class="site-main"><div class="site-hero"><p class="site-eyebrow">${page.eyebrow}</p><h1>${page.title}</h1><p class="site-lead">${page.lead}</p></div><div class="site-content">${page.content}</div></main>
  <footer class="site-footer"><div class="site-footer-inner"><div class="site-footer-brand">${logo}<p class="site-footer-note">Move well. Build good habits. Keep going.</p></div><div><h2>Explore</h2><a href="about.html">About us</a><a href="programs.html">Programs</a><a href="trainers.html">Trainers</a><a href="pricing.html">Pricing</a><a href="membership-plans.html">Membership plans</a><a href="contact.html">Contact</a></div><div><h2>Help &amp; policies</h2><a href="faqs.html">FAQs</a><a href="terms.html">Terms &amp; Conditions</a><a href="privacy.html">Privacy policy</a><a href="cookies.html">Cookies policy</a><a href="legal.html">Legal information</a></div></div><div class="site-footer-bottom">Fitness Hub · Project demo</div></footer>`;

const contactForm = document.getElementById("contact-demo-form");
contactForm?.addEventListener("submit", (event) => {
  event.preventDefault();
  const senderName = new FormData(contactForm).get("name");
  document.getElementById("contact-demo-message").textContent = `Thanks, ${senderName}. Your message is still only in this form; it has not been sent or saved.`;
});
