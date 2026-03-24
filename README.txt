=== ISWP Custom Progress Bar ===
Contributors: Juan Manuel Luna López
Donate link: https://lunalopez.ml
Tags: progress bar, learndash, gravity forms, certificate, user profile
Version: 1.0.0

== Description ==

ISWP Custom Progress Bar adds a custom 5-step certification workflow to WordPress.

It was built for a LearnDash-based process where users must complete a mix of
quizzes, a course, a Gravity Form submission, and a manually validated payment.
The plugin displays progress on the user profile, provides admin tools to manage
and review completion, generates a certificate, and sends renewal reminder e-mails.

This is not a generic LMS progress indicator. It is a custom workflow layer on
top of LearnDash, Gravity Forms, and WordPress user meta.

== Features ==

1. Frontend progress bar
- Rendered with the shortcode [iswp_custom_progress_bar]
- Shows 5 steps and the user's completion percentage
- Incomplete steps show their number
- Completed steps show their configured name
- Each step can link to its corresponding page
- When completion reaches 100%, a "View Certificate" link is displayed

2. Configurable workflow
- Editable title and progress bar color
- Editable step names, links, and IDs
- Supports LearnDash quizzes/courses, Gravity Forms, and manual payment validation

3. Admin tools
- User List tab with completion status for Steps 1 to 5
- Steps Settings tab
- E-mail Settings tab
- Payment section on the WordPress user edit screen

4. Dynamic certificate generation
- Generates a PNG certificate using a base image and font files
- Includes the user's name and certificate validity dates
- Becomes available only after the full workflow is completed

5. Scheduled reminder e-mails
- Runs twice daily via WordPress cron
- Sends reminders based on the stored payment date and certificate expiration window

== Default Workflow ==

The default 5-step setup is:

1. Basic Knowledge Test
2. Ethics and Professionalism Course
3. Ethics and Professionalism Test
4. Supporting Documents
5. Payment

All labels, links, and IDs can be edited from the plugin settings.

== Where Everything Lives ==

Plugin bootstrap:
- iswp-custom-progress-bar.php

Core loader:
- includes/class-iswp-custom-progress-bar.php

Frontend logic:
- public/class-iswp-custom-progress-bar-public.php

Frontend assets:
- public/css/iswp-custom-progress-bar-public.css
- public/js/iswp-custom-progress-bar-public.js

Admin logic:
- admin/class-iswp-custom-progress-bar-admin.php

Admin wrapper:
- admin/partials/iswp-custom-progress-bar-admin-display.php

Admin tabs:
- User List:
  - admin/partials/iswp-cpb-admin--user_list.php
  - admin/partials/iswp-cpb-admin--user_list--User_Steps_List_Table.php
- Steps Settings:
  - admin/partials/iswp-cpb-admin--steps_settings.php
- E-mail Settings:
  - admin/partials/iswp-cpb-admin--email_settings.php

Certificate logic:
- includes/class-iswp-cpb--certificate.php

E-mail / cron logic:
- includes/class-iswp-cpb--email_queries.php

Certificate assets:
- assets/certificate_english.png
- assets/arial_bold.ttf
- assets/calibri_bold.ttf

== Admin Screens ==

1. Tools > ISWP Progress Bar
Admin URL:
- tools.php?page=iswp-custom-progress-bar

Tabs:
- User List
- Steps Settings
- E-mail Settings

User List
- Default tab
- Shows a WP_List_Table with:
  - ID
  - Display Name
  - User Login
  - S1
  - S2
  - S3
  - S4
  - S5

Steps Settings
Admin URL:
- tools.php?page=iswp-custom-progress-bar&tab=steps_settings

Used to configure:
- Bar title
- Progress bar background color
- Step 1 quiz ID, name, and link
- Step 2 course ID, name, and link
- Step 3 quiz ID, name, and link
- Step 4 form ID, name, and link
- Step 5 name and link

Stored option:
- iswp_cpb__oname__steps_settings

E-mail Settings
Admin URL:
- tools.php?page=iswp-custom-progress-bar&tab=email_settings

Used to configure:
- M0 subject and content
- M1 subject and content
- M2 subject and content
- M3 subject and content

Stored option:
- iswp_cpb__oname__email_settings

2. Users > Edit User
The plugin adds an "ISWP Progress Bar" section to the user edit screen.

Used to manage:
- User paid the fees
- Date of last payment

User meta managed here:
- _wsp_payment_received
- _wsp_payment_date

Note:
- _wsp_last_email is stored internally for reminder logic, but is not exposed in
  the user edit UI.

== Frontend Usage ==

Shortcode:
[iswp_custom_progress_bar]

Shortcode rendering:
- Registered in public/class-iswp-custom-progress-bar-public.php
- Rendered by progress_bar_draw()
- Main HTML assembled by pb_div_render()

Original usage context:
- The shortcode was intended to be placed on the user profile page
- In the original project, the bar was displayed on /profile/

Important frontend note:
- public/js/iswp-custom-progress-bar-public.js looks for a .profile_info element
  and repositions the progress bar there
- If your profile page uses a different structure, adjust that script accordingly

== Step Logic ==

Step 1
Type:
- LearnDash quiz
Completion:
- Completed when the configured quiz is completed for the user

Step 2
Type:
- LearnDash course
Completion:
- Completed when the configured course is completed for the user

Step 3
Type:
- LearnDash quiz
Completion:
- Completed when the configured quiz is completed for the user

Step 4
Type:
- Gravity Forms submission
Completion:
- Completed when the logged-in user has at least one active entry in the
  configured Gravity Form
- Entries in spam or trash do not count

Step 5
Type:
- Manual payment validation
Completion:
- Completed when _wsp_payment_received is set for the user

== Certificate ==

The certificate is generated dynamically as a PNG image.

Logic:
- includes/class-iswp-cpb--certificate.php

Trigger:
- The progress bar displays a "View Certificate" link when completion is 100%

Output URL pattern:
- /wp-admin/admin-post.php?action=iswp-cpb__certificate

Requirements:
- User must have completed all 5 steps
- Step 5 depends on _wsp_payment_received
- Certificate dating depends on a valid _wsp_payment_date

Validity:
- Effective from: payment date
- Effective to: payment date + 2 years

Important:
- Step 5 and certificate generation are related but not identical
- Marking payment as received completes Step 5
- A valid payment date is also required for certificate date calculation

== E-mails and Cron ==

Cron hook:
- iswpcpb__cron_hook__emails

Defined in:
- includes/class-iswp-custom-progress-bar.php

Handled by:
- includes/class-iswp-cpb--email_queries.php

Frequency:
- twicedaily

Mail engine:
- wp_mail()

Reminder schedule:
- M0: on the payment date
- M1: 6 months before expiration
- M2: 3 months before expiration
- M3: 1 week before expiration

Template token supported:
- [NAME]

Expiration rule:
- payment date + 2 years

Recommended companion plugins:
- WP Crontrol
- WP Mail Logging

== Dependencies ==

Required:
- WordPress
- LearnDash
- Gravity Forms
- PHP GD extension

Recommended:
- SMTP mail plugin such as WP Mail SMTP
- WP Crontrol
- WP Mail Logging

== Setup ==

1. Install and activate the plugin.

2. Confirm dependencies are available:
- LearnDash
- Gravity Forms
- PHP GD
- Mail delivery plugin if needed

3. Add the shortcode to the target profile page:
- [iswp_custom_progress_bar]

4. Confirm the target page contains a .profile_info element.
If not, update:
- public/js/iswp-custom-progress-bar-public.js

5. Go to:
- Tools > ISWP Progress Bar > Steps Settings
Configure:
- bar title
- bar color
- step names
- step links
- LearnDash quiz/course IDs
- Gravity Form ID
- payment page link

6. Go to:
- Tools > ISWP Progress Bar > E-mail Settings
Configure:
- M0 to M3 subjects
- M0 to M3 message bodies

7. Go to:
- Users > Edit User
Set:
- User paid the fees
- Date of last payment

8. Test the workflow with a user who has completed all steps.
Verify:
- progress reaches 100%
- "View Certificate" appears
- certificate renders correctly
- cron and outgoing mail work as expected

== Data Stored ==

Options:
- iswp_cpb__oname__steps_settings
- iswp_cpb__oname__email_settings

User meta:
- _wsp_payment_received
- _wsp_payment_date
- _wsp_last_email

== Notes ==

- The plugin is designed around a specific certification workflow.
- The original frontend placement depended on a profile page structure that
  includes .profile_info.
- The certificate is generated on request and is not stored as a static file.
- If reusing this plugin on another site, review first:
  - step links
  - LearnDash IDs
  - Gravity Form ID
  - profile page markup
  - mail delivery
  - certificate assets