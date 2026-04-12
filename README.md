# Velocity

A Real-Time Kanban Project Management System

Built with Laravel, Reverb WebSocket, Livewire, and Bootstrap. Self-hosted on a two-server infrastructure with Cloudflare Tunnel, Nginx, PHP-FPM, and Portainer.

---

## Overview

Velocity is a full-stack, real-time project management application built in the Kanban style. It allows teams to create workspaces, manage boards and tasks, and collaborate in real time. Every card move, label change, and comment is broadcast instantly to all connected team members using Laravel Reverb WebSocket, without requiring a page refresh.

The project was built from scratch over five months as a self-directed learning exercise, with no prior experience in most of the technologies used. Primary references included official documentation and AI-assisted debugging, primarily using Google Gemini.

---

## Features

### Authentication and Access

- Email and password registration with email verification
- OAuth login via Google and GitHub
- Role-based access within workspaces (owner, admin, member)
- Email invitation system using SMTP for onboarding new team members

### Project and Board Management

- Create and manage multiple workspaces
- Kanban-style boards with drag-and-drop card ordering
- Lists and cards with full CRUD support
- Card labels, due dates, descriptions, and checklist items
- Card archiving and activity log per card

### Real-Time Collaboration

- Live board updates broadcast over WebSocket using Laravel Reverb
- All connected team members see changes instantly when a card is moved, edited, or deleted
- Presence channels reflect who is currently viewing a board

### Infrastructure and Deployment

- Self-hosted across two servers: a dedicated database server and an application/proxy server
- Cloudflare Tunnel used for secure inbound routing without exposing ports directly
- Nginx reverse proxy on Alpine Linux with PHP-FPM
- Containerized with Docker and managed via Portainer
- Laravel Reverb runs as a persistent WebSocket server alongside the web process

---

## Technology Stack

| Layer | Technology | Notes |
|---|---|---|
| Backend Framework | Laravel 11 | PHP 8.2 |
| Frontend | Blade + Livewire | Reactive UI without a separate JS framework |
| CSS Framework | Bootstrap 5 | Custom theme overrides |
| WebSocket Server | Laravel Reverb | Self-hosted, replaces Pusher |
| Database | MySQL | Separate dedicated server |
| Authentication | Laravel Socialite | Google and GitHub OAuth |
| Email | SMTP | Invitation and verification emails |
| Web Server | Nginx | Alpine Linux container |
| PHP Handler | PHP-FPM | FastCGI process manager |
| Containerization | Docker | Managed via Portainer |
| Tunnel | Cloudflare Tunnel | Secure public access |

---

## Infrastructure Architecture

The deployment spans two physical servers.

### Application Server

- Runs Nginx on Alpine Linux as the reverse proxy
- PHP-FPM processes Laravel application requests
- Laravel Reverb runs as a long-lived WebSocket process on a dedicated port
- Cloudflare Tunnel handles all inbound HTTPS and WebSocket traffic
- All services are containerized and managed through Portainer

### Database Server

- Dedicated MySQL server, isolated from the application server
- Accessible to the application server over a private network interface
- Not exposed to the public internet

### Network and Traffic Flow

```
Client (Browser)
     |
     v
Cloudflare CDN / DNS
     |
     v
Cloudflare Tunnel (cloudflared daemon on App Server)
     |
     v
Nginx (Alpine container)
     |--- HTTP  ---> PHP-FPM ---> Laravel App
     |--- WS    ---> Laravel Reverb (WebSocket Server)
                          |
                          v
                   MySQL Server (Database Server)
```

---

## Database Schema

The schema is included in this repository as `velocity_schema.sql`. Key entities include:

- `users` - Accounts created via email or OAuth
- `workspaces` - Top-level organizational unit
- `workspace_members` - Pivot table with role assignments
- `boards` - Kanban boards belonging to a workspace
- `lists` - Ordered columns within a board
- `cards` - Tasks within a list, with ordering support
- `card_labels`, `card_checklists`, `card_activities` - Card metadata and audit trail
- `invitations` - Pending workspace invitations sent by email

---

## OAuth Application Setup

### Google OAuth

- Go to [console.cloud.google.com](https://console.cloud.google.com) and create a new project
- Enable the Google+ API or People API
- Create OAuth 2.0 credentials under APIs and Services
- Add your callback URL: `https://yourdomain.com/auth/google/callback`
- Copy the client ID and secret into your `.env` file

### GitHub OAuth

- Go to [github.com/settings/developers](https://github.com/settings/developers) and register a new OAuth App
- Set the Authorization callback URL to `https://yourdomain.com/auth/github/callback`
- Copy the client ID and secret into your `.env` file

---

## Production Deployment Notes

### Nginx Configuration

Nginx is configured to route standard HTTP requests to PHP-FPM and WebSocket upgrade requests to the Reverb process. The relevant configuration must handle:

- PHP-FPM `fastcgi_pass` for all `.php` requests
- Proxy pass to the Reverb port (default `8080`) for WebSocket connections
- WebSocket headers: `Upgrade` and `Connection` must be forwarded correctly

### Cloudflare Tunnel

A `cloudflared` daemon runs on the application server and maintains a persistent outbound tunnel to Cloudflare. This eliminates the need to open inbound firewall ports. DNS is managed through Cloudflare, and the tunnel handles both HTTPS and WebSocket traffic.

### Process Management

- Reverb runs as a supervised background process inside its container
- Queue workers for email dispatch are also run as persistent supervised processes
- Portainer provides a web UI for container lifecycle management and log inspection

---

## Repository Contents

| File / Directory | Description |
|---|---|
| `notion-budget/` | Main Laravel application source code |
| `velocity_schema.sql` | Full MySQL schema dump |
| `schema-diagram.png` | Entity-relationship diagram |
| `Logo.png` / `Logo.ai` | Project logo assets |

---

## Project Background

Velocity was built over five months as a personal learning project, starting with no prior experience in Laravel, Livewire, WebSockets, or server infrastructure. The development process relied heavily on official documentation and AI-assisted debugging, with Google Gemini used as the primary debugging assistant throughout the project.

The scope expanded progressively as each subsystem was learned: starting with basic Laravel MVC, then Livewire reactivity, then real-time broadcasting with Reverb, then OAuth integration, and finally self-hosted infrastructure with Docker, Nginx, and Cloudflare Tunnel.

The project represents a complete end-to-end implementation of a production-grade collaborative tool, built and deployed independently.

---

## License

This project is open source and available for personal and educational use. No formal license is attached. Attribution is appreciated but not required.
