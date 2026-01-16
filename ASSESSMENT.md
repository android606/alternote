# Assessment: "nextnote" References in Alternote App

## Summary
Searched entire app for case-insensitive "nextnote" references and categorized findings.

## Critical Issues - FIXED ✅
1. **SettingsService.php** - All `getAppValue('nextnote')` and `setAppValue('nextnote')` calls → Changed to `'alternote'`
2. **AdminSettings.php** - `TemplateResponse('nextnote')` and `getSection()` → Changed to `'alternote'`
3. **PersonalSettings.php** - `TemplateResponse('nextnote')` and `getSection()` → Changed to `'alternote'`
4. **AdminSection.php** - `getID()`, `getName()`, `imagePath('nextnote')` → Changed to `'alternote'`
5. **PersonalSection.php** - `getID()`, `getName()`, `imagePath('nextnote')` → Changed to `'alternote'`
6. **templates/admin.php** - `addScript('nextnote')` and `getL10N('nextnote')` → Changed to `'alternote'`
7. **templates/settings-personal.php** - `addScript('nextnote')` and `getL10N('nextnote')` → Changed to `'alternote'`

## Important Issues - Should Fix
8. **js/admin.js** - Uses `OC.linkTo("nextnote")` and `"apps/nextnote"` in URLs
   - Status: Legacy file, may still be used for admin settings
   - Impact: Admin settings page may not work correctly
   
9. **js/user.js** - Uses `OC.linkTo("nextnote")` and `"apps/nextnote"` in URLs
   - Status: Legacy file, may still be used for personal settings
   - Impact: Personal settings page may not work correctly

10. **Old AngularJS files** (js/app/*.js) - Use `'apps/nextnote'` in API URLs
    - Status: Legacy files, replaced by Vue.js frontend
    - Impact: None if Vue.js is working correctly
    - Files: NoteFactory.js, NotebookFactory.js, app.js

## Low Priority - Cosmetic/Documentation
11. **Translation files (l10n/)** - Display names like "Nextnote"/"NextNote"
    - Status: Translation strings, display names only
    - Impact: Cosmetic - app will show "Nextnote" in some translations
    - Recommendation: Can be updated later for consistency

12. **Comments in PHP files** - Header comments mentioning "NextNote"
    - Status: Documentation only
    - Impact: None
    - Recommendation: Update for consistency

13. **Database table names** - `nextnote_notes`, `nextnote_parts`, `nextnote_groups`
    - Status: Actual database table names
    - Impact: None - these are correct table names
    - Recommendation: Keep as-is (table names don't need to match app name)

## Migration Files
14. **migration/migrategroups.php** - References old `nextnote` table
    - Status: Migration script for old data
    - Impact: None if migration already completed
    - Recommendation: Keep as-is (historical migration)

## Remaining Counts
- PHP files with 'nextnote' (excluding table names): 0 critical references remaining
- JS files with 'nextnote': ~20+ references (legacy AngularJS files)
- Translation files: ~100+ references (display names only)

## Recommendations
1. ✅ **DONE**: Fixed all critical PHP references
2. **TODO**: Update js/admin.js and js/user.js if settings pages are used
3. **OPTIONAL**: Update translation files for consistency
4. **OPTIONAL**: Update comments in PHP files
5. **KEEP**: Database table names and migration files as-is
