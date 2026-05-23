## 1) How long did you spend on the coding test? What would you add if you had more time?

I spent ~2-3 hours on this implementation.

If I had more time, I would add:
- **Real upload support** for images (instead of asking for image paths). This includes server-side validation, storage, and returning uploaded paths.
- **Better data model**: the current approach treats each DB row as one “tab/slide” pair. If the design truly requires multiple images per tab (column 2 slides within a tab), I’d normalize tables (e.g., `sections` + `section_slides`) and render per-tab carousels.
- **Authentication + authorization** for the admin CRUD (basic login and role checks).
- **Validation & error handling UI** (field-level errors, loading spinners, and robust empty-state rendering).
- **Accessibility improvements** (ARIA labels, focus management, keyboard interactions aligned with Bootstrap components).

## 2) How would you track down a performance issue in production? Have you ever had to do this?

To track down performance issues in production:
- **Establish metrics first**: use APM (e.g., New Relic / Datadog / Elastic APM), check latency percentiles (p50/p90/p99), and correlate with errors/retries.
- **Check server-side bottlenecks**:
  - PHP timing (request duration breakdown)
  - MySQL slow query log + EXPLAIN plans
  - connection pooling/limits
- **Client-side performance**:
  - Lighthouse/WebPageTest
  - browser performance profiles (Long Tasks, network waterfalls, JS bundle size)
- **Logging & tracing**: add structured logs around slow operations (DB queries, external calls) and trace request IDs through the stack.
- **Reproduce and isolate**: run load tests against the failing endpoints and compare query plans/index usage.

Yes—performance investigations are common in real projects (e.g., query regressions, missing indexes, N+1 patterns, slow third-party calls). The typical fix is improving query/indexing, caching, or reducing payload/DOM work.

## 3) Please describe yourself using JSON.

```json
{
  "name": "BLACKBOXAI",
  "role": "Software Engineer",
  "strengths": [
    "backend (PHP/PDO, APIs)",
    "frontend (Bootstrap/jQuery)",
    "database design (MySQL schema, indexing)",
    "debugging and performance analysis"
  ],
  "style": "pragmatic, test-driven mindset, secure-by-default",
  "values": ["clarity", "maintainability", "performance", "accessibility"],
  "availability": "async",
  "notes": "Builds small, working systems end-to-end; iterates based on feedback."
}
```

