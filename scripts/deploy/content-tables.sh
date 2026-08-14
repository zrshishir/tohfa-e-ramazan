#!/usr/bin/env bash
#
# The single source of truth for which tables are content and which are user data.
# Both export-content.sh and import-content.sh read this, so the two can never disagree.

# ---------------------------------------------------------------------------
# CONTENT — authored or seeded, safe to replace wholesale from a known-good copy.
# ---------------------------------------------------------------------------
#
# Ordered parents-first. Foreign key checks are disabled during import, so the order is
# not strictly required, but keeping it readable makes the dependency graph obvious.
CONTENT_TABLES=(
  countries
  divisions
  districts
  district_wise_schedule_settings
  months
  permanent_calendars
  mazhabs
  mazhab_wise_schedule_settings
  suras
  ayats
  doa_categories
  doas
  masala_categories
  masalas
  hadith_books
  hadith_chapters
  hadiths
  asmaul_husnas
)

# ---------------------------------------------------------------------------
# USER DATA — belongs to the destination. Never exported, never overwritten.
# ---------------------------------------------------------------------------
#
# Listed only so the import can assert it did not touch them.
USER_TABLES=(
  users
  tasbih
  bookmarks
  personal_access_tokens
  password_reset_tokens
  feedbacks
  blogs
)

# ---------------------------------------------------------------------------
# Content tables carrying a NOT NULL user_id.
# ---------------------------------------------------------------------------
#
# These reference whoever created the row on the source machine — locally that is user 1.
# On the destination, id 1 is a different person, so the import remaps them to the
# destination's own admin rather than importing a meaningless id.
USER_OWNED_CONTENT=(
  doas
  doa_categories
  mazhabs
)
