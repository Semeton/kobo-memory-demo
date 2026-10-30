---
name: remember
description: Record a team decision, constraint or lesson in the repository's memory log as a proposal for human review. Use when the developer says "remember that…", "note that we…", "record this decision", or asks you to make sure future sessions know something about this codebase. Also use, after asking the developer, when a task surfaced something the code doesn't show — a reason they gave, a conflict with a record you resolved, a record you found stale, or an approach rejected for a non-obvious reason.
---

# Remember: propose a record for the team's memory

The team keeps its decisions in `.agent/memory/decisions.jsonl`, one JSON record per line. You can **propose** a record. Only a human can accept it, in a reviewed pull request. Never edit `decisions.jsonl` by hand, and never run `php tools/memory accept` or `reject`.

## When to offer this yourself

At the end of a task, offer (in one line, never automatically) to propose a record when the task surfaced something a future session would need and the code doesn't show:

- the developer gave a reason ("we can't, because finance…");
- the ticket conflicted with a record and you resolved it, or the developer decided how to resolve it;
- a record turned out stale or wrong (its evidence is missing, or the code now works differently). Propose a replacement with `"supersedes": ["<old id>"]`;
- you rejected an approach for a reason the code doesn't show.

Don't offer for things the code or tests already make obvious.

## Steps

1. **Check it isn't already recorded.** Run `php tools/memory for <a path the rule applies to>` and grep the log for the key terms. If a live record already says this, show it to the developer and stop. If the new rule contradicts a live record, say so plainly, and propose the new one with `"supersedes": ["<old id>"]` in its text only after the developer confirms.

2. **Decide whether it should be a record at all.** If the rule can be fully enforced by a test, a type or an architecture rule, offer to write that instead, and record only the reason if there is one the code can't carry. If the code already makes it obvious, say so and stop.

3. **Draft the record from what the developer said.** Use their words where you can.
   - `title`: one line.
   - `rule`: what to do, imperative and checkable.
   - `why`: the reason the code can't tell you (an incident, a customer constraint, a past attempt), and the mistake this record will prevent. Keep the event (what happened) separate from the rule (what to do now): the event is history and never changes; the rule is current policy. If the developer gave no reason, ask for one in a single short question. A record without a why is a rule nobody can safely change.
   - `revisit_when`: what would make the team reconsider the rule (e.g. "finance stops reconciling by order number"). Ask if it isn't obvious.
   - `scope`: path globs where the rule applies, e.g. `app/Modules/Orders/**`. Keep it as narrow as the rule allows.
   - `evidence`: at least one class, path or test that makes the rule true **in the code today**. Find it, and confirm it exists. Use `{"kind":"class","ref":"App\\..."}`, `{"kind":"path","ref":"..."}` or `{"kind":"test","ref":"tests/..."}`. If nothing in the code anchors the rule yet, say so, and suggest a test that would.
   - `kind`: `decision`, `constraint`, `convention` or `finding`.
   - `source`: `{"by":"agent","who":"claude-code","ref":"<one line on where this came from, e.g. the developer's request>"}`.

4. **Show the draft to the developer** and get a yes before writing anything.

5. **Propose it.** Write the record as JSON to `.agent/memory/proposal.json` with your file-writing tool. Don't include `id` or `status`: the tool assigns both. Then run:

   ```bash
   php tools/memory propose --file .agent/memory/proposal.json
   ```

   It appends the record as `proposed`, prints its ID and deletes the file. Then run `php tools/memory check` to confirm the evidence resolves.

6. **Hand it back.** Tell the developer the record ID the tool printed (never invent one), that it is **proposed** (it won't reach other agents yet), and that to accept it they run `php tools/memory accept <id>`, then `php tools/memory index`, and commit both files in a pull request.

7. **If the rule can be executed, offer to make it a test.** If it can be expressed as a Pest architecture test or a feature test, offer to write that test and add it as `test` evidence. The test enforces the what; the record keeps the why.
