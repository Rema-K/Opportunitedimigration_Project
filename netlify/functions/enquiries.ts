import type { Config } from "@netlify/functions";
import { getStore } from "@netlify/blobs";

const COUNTRIES = new Set([
  "Canada", "Italy", "Luxembourg", "Germany", "France",
  "United States", "United Kingdom", "Switzerland", "China", "Turkey",
]);
const PROJECTS = new Set(["study", "work", "travel", "immigration", "other"]);
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

const str = (v: unknown, max: number) => (typeof v === "string" ? v.trim().slice(0, max) : "");

export default async (req: Request) => {
  if (req.method !== "POST") {
    return Response.json({ ok: false, error: "method_not_allowed" }, { status: 405 });
  }

  let body: Record<string, unknown>;
  try {
    body = await req.json();
  } catch {
    return Response.json({ ok: false, error: "invalid_json" }, { status: 400 });
  }

  const fullName = str(body.full_name, 120);
  const email = str(body.email, 254);
  const phone = str(body.phone, 40);
  const destination = str(body.destination, 80);
  const projectType = str(body.project_type, 20);
  const message = str(body.message, 3000);
  const language = body.language === "en" ? "en" : "fr";
  const consent = body.consent === true;

  if (!fullName || !EMAIL_RE.test(email) || !phone || !COUNTRIES.has(destination) || !PROJECTS.has(projectType) || !consent) {
    return Response.json({ ok: false, error: "validation" }, { status: 422 });
  }

  try {
    // One JSON record per enquiry, keyed by timestamp + random id so keys sort by date.
    const createdAt = new Date().toISOString();
    const key = `${createdAt}_${crypto.randomUUID()}`;
    await getStore({ name: "enquiries", consistency: "strong" }).setJSON(key, {
      full_name: fullName,
      email,
      phone,
      destination,
      project_type: projectType,
      message: message || null,
      consent_given: true,
      preferred_language: language,
      status: "new",
      created_at: createdAt,
    });
    return Response.json({ ok: true }, { status: 201 });
  } catch (err) {
    console.error("Opportunitedimigration enquiry save failed:", err);
    return Response.json({ ok: false, error: "server" }, { status: 500 });
  }
};

export const config: Config = {
  path: "/api/enquiries",
};
