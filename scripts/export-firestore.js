/**
 * Exporta datos de Firestore a archivos JSON
 * Uso: node scripts/export-firestore.js
 */
import { initializeApp, cert, getApps } from "firebase-admin/app";
import { getFirestore } from "firebase-admin/firestore";
import { readFileSync, writeFileSync, existsSync } from "node:fs";
import path from "node:path";
import { fileURLToPath } from "node:url";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const keyPath = path.resolve(__dirname, "../../invicta-costarica-firebase-adminsdk-fbsvc-a2e0fc0ce2.json");

if (!existsSync(keyPath)) {
  console.error("Service account file not found at:", keyPath);
  process.exit(1);
}

const serviceAccount = JSON.parse(readFileSync(keyPath, "utf8"));

if (!getApps().length) {
  initializeApp({ credential: cert(serviceAccount) });
}

const db = getFirestore();
const outputDir = path.resolve(__dirname, "../storage/app/firebase-export");

// Colecciones a exportar
const collections = [
  "products", "productos", "colecciones", "invoices", "clients",
  "expenses", "suscriptores", "combos", "product_comments",
  "settings", "sync_logs", "marketing_tasks"
];

async function exportCollection(name) {
  console.log(`Exporting ${name}...`);
  try {
    const snapshot = await db.collection(name).get();
    const docs = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
    writeFileSync(`${outputDir}/${name}.json`, JSON.stringify(docs, null, 2));
    console.log(`  -> ${docs.length} documents exported`);
    return docs;
  } catch (e) {
    console.log(`  -> Error: ${e.message}`);
    return [];
  }
}

import { mkdirSync } from "node:fs";
mkdirSync(outputDir, { recursive: true });

const results = {};
for (const col of collections) {
  results[col] = await exportCollection(col);
}

console.log("\n=== Export Summary ===");
let total = 0;
for (const [col, docs] of Object.entries(results)) {
  console.log(`${col}: ${docs.length} docs`);
  total += docs.length;
}
console.log(`Total: ${total} documents`);
console.log(`Output: ${outputDir}`);
