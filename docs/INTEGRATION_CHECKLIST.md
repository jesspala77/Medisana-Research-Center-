# Integration Checklist

- [ ] Confirm SynNexus repository root and active branch
- [ ] Confirm Laravel boots before changes
- [ ] Confirm `ConstructionProject` model/table names
- [ ] Confirm auth middleware (`auth` / `auth:sanctum`)
- [ ] Copy migrations and run them on local SQLite first
- [ ] Import a five-row test CSV
- [ ] Run queue worker
- [ ] Verify review queue
- [ ] Approve one direct material transaction and confirm project cost created
- [ ] Approve one overhead transaction and confirm no project cost created
- [ ] Test duplicate import prevention
- [ ] Enable AI only after deterministic flow passes
- [ ] Never send raw bank account numbers or card numbers to AI
