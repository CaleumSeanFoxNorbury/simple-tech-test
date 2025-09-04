# Tech Test: NHS ODS ORD API Search

## Overview

Build a tiny PHP + vanilla JS app that queries the NHS ODS ORD Search endpoint and shows active organisations as simple UI cards.

You will use the Search endpoint described here:

- https://digital.nhs.uk/services/organisation-data-service/organisation-data-service-apis/technical-guidance-ord/search-endpoint

---

## What you will build

A single page that:

1) Accepts `PostCode` and `Name` input values  
2) Calls the ODS ORD Search endpoint using those inputs  
3) Filters to active organisations only  
4) Displays results as tidy cards with Name, ODS code, and Status  
5) Adds basic styling so it looks neat  
6) Bonus: a small client-side filter and optional pagination

You should also add a short code comment that explains how you identified the correct Primary Role for your query. A hint is below. Do not hardcode answers in this file.

---

## Requirements

***Constraints:***
- Constraints
- PHP for the tiny API proxy
- Vanilla JavaScript in the browser
- No frameworks or build steps
- Keep logic split into small functions

### 1: Search request

Build a query string with the following parameters at minimum:

- `PostCode`  
- `Name` with a contains search pattern, for example `%{name}%`  
- `Status=Active`  
- One of `PrimaryRoleId` or `Roles` to target a specific role  
- Optional `Limit` and `Offset` for paging

Example pattern to construct the URL: `https://directory.spineservices.nhs.uk/ORD/2-0-0/organisations?PostCode={postcode}&Name=%{name}%&Status=Active&PrimaryRoleId={roleId}&Limit=50`

You can also search using `Roles` if you prefer that style.

If helpful, you may build the `urlPart` in pieces first:

```php
$urlPart = '?PostCode=' . urlencode($postcode) . '&Name=%' . urlencode($name) . '%';
$urlPart = '?PostCode=' . urlencode($postcode) . '&Name=%' . urlencode($streetAddress) . '%';
$fullUrl = 'https://directory.spineservices.nhs.uk/ORD/2-0-0/organisations' . $urlPart;
```

### 2: Primary Role explanation

In your code, add a short comment that explains how you found the Primary Role you decided to use.
Hint: the Search endpoint documentation shows parameters that let you constrain by role, for example PrimaryRoleId or Roles. You can use the docs to identify the correct value for the role you want to target.

### 3: Render cards

From the response, render a grid of cards. For each organisation show:

Name

ODS code (OrgId)

Status

Only display organisations where Status is Active.

### 4: Make it presentable

Add a minimal responsive card layout and basic states:

Loading

Empty results

Error

### 5: Bonus points [Not Mandatory]

A small text filter that hides non-matching cards on the client

Simple pagination using Limit and Offset

An input for PrimaryRoleId or Roles so the user can change the role


***Running the porject locally:***
```
cd public
php -S 127.0.0.1:8080
```
Visit http://127.0.0.1:8080

### NOTE: Keep it small and readable please. If you have any questions please contact me on kez@thefamilychemist.co.uk. Enjoy and Good luck 🚀
