# Implementation Plan - "Give & Take" Community Sharing Platform

This plan outlines the database schema, features, and step-by-step implementation for the "Give & Take" (Cho & Nhận) used item sharing and exchange platform.

---

## 1. Project Specifications
- **Framework:** Laravel 11 (PHP 8.2+)
- **Frontend:** Laravel Livewire v3 + Tailwind CSS + Alpine.js
- **Database:** MySQL
- **Real-time Chat:** Laravel Reverb (WebSocket)

---

## 2. Database Schema Design

### `users` (Modified from default)
- `id` (bigint, PK)
- `name` (varchar)
- `email` (varchar, unique)
- `password` (varchar)
- `phone` (varchar, nullable)
- `city` (varchar, nullable) - For local search
- `district` (varchar, nullable) - For local search
- `karma_points` (int, default: 50) - Earned by giving, spent by receiving
- `trust_score` (decimal(3,2), default: 5.0) - User rating (1.00 to 5.00)
- `remember_token`, `timestamps`

### `categories`
- `id` (bigint, PK)
- `name` (varchar) - e.g., Clothing, Electronics, Books, Household
- `slug` (varchar, unique)
- `timestamps`

### `items` (Items listed for sharing/exchange)
- `id` (bigint, PK)
- `user_id` (bigint, FK -> users)
- `category_id` (bigint, FK -> categories)
- `title` (varchar)
- `description` (text)
- `images` (json) - Array of image file paths
- `type` (enum: 'give', 'exchange')
- `exchange_wish` (text, nullable) - What they want in return if type is 'exchange'
- `status` (enum: 'available', 'reserved', 'completed')
- `city` (varchar)
- `district` (varchar)
- `timestamps`

### `item_requests` (When a user clicks "Xin đồ" / "Request Exchange")
- `id` (bigint, PK)
- `item_id` (bigint, FK -> items)
- `user_id` (bigint, FK -> users, requester)
- `message` (text) - Quick note explaining why they need it or what they offer
- `status` (enum: 'pending', 'approved', 'rejected')
- `timestamps`

### `chat_rooms` (Automatically created when a request is approved)
- `id` (bigint, PK)
- `item_request_id` (bigint, FK -> item_requests)
- `timestamps`

### `chat_messages`
- `id` (bigint, PK)
- `chat_room_id` (bigint, FK -> chat_rooms)
- `user_id` (bigint, FK -> users, sender)
- `message` (text)
- `is_read` (boolean, default: false)
- `timestamps`

---

## 3. Implementation Steps

### Phase 1: Setup & Initialization
1. Create a new directory `/home/vnj-dev/GiveTake`.
2. Initialize a fresh Laravel 11 project.
3. Install Laravel Breeze (Livewire Functional/Class components style) for authentication.
4. Install Tailwind CSS.
5. Create migrations, models, and factories.

### Phase 2: Core Features (Livewire Components)
1. **Home / Discovery Page:**
   - Grid listing of available items.
   - Filters: category, type (give/exchange), city, district.
   - Search bar.
2. **Item Details Page:**
   - Image carousel, description, user trust score.
   - "Request Item" button triggering a popup form to enter message/offer.
3. **User Dashboard / Item Management:**
   - "Post New Item" form with image upload (multiple files).
   - "My Listings" tab (Active, Reserved, Completed).
   - "My Requests" tab (Tracking items requested from others).
4. **Request Approval Panel:**
   - For item owners to view requesters and approve one.
   - Triggers Karma point deduction (-5 for requester, +10 for giver) and creates a chat room.

### Phase 3: Communication & Chat (Real-time)
1. Set up Laravel Reverb.
2. Create dynamic Chat Room Livewire component.
3. Add notifications (database/mail) when a request is made or approved.

### Phase 4: Polish & Styling
1. Apply premium, clean responsive layouts.
2. Verify all user flows (Listing -> Requesting -> Approving -> Chatting -> Completing).

---

## 4. How to run this autonomously
To allow me to build this project entirely in the background while you open other chats or do other work, please use the `/goal` slash command:

```text
/goal Initialize the new Laravel 11 project in /home/vnj-dev/GiveTake and build the Give & Take platform according to the implementation plan in /home/vnj-dev/GiveTake/implementation_plan.md
```
