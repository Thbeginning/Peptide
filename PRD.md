# Product Requirements Document (PRD) - Qingli Peptide

## 1. Project Overview
**Qingli Peptide** is a professional B2B e-commerce and information platform for high-purity research peptides. The platform is designed specifically for laboratory and clinical research use, emphasizing purity, quality assurance, and secure B2B transactions through a quote-based inquiry system rather than direct consumer retail.

### Vision
To be the most trusted global supplier of research-grade peptides by providing verifiable quality data, transparent communication, and scientific tools for the research community.

---

## 2. Target Audience
- **Laboratory Researchers**: Scientists requiring high-purity compounds for in-vitro or in-vivo studies.
- **Biotech Institutions**: Organizations looking for reliable wholesale suppliers.
- **Clinical Researchers**: Professionals focused on peptide-based therapeutic studies.

---

## 3. Key Features & Functionalities

### 3.1. User Authentication & Management
- **Role-Based Access**: Support for 'Customer' and 'Admin' roles.
- **Secure Login/Registration**: Email-based authentication with password hashing.
- **Account Dashboard**: Customers can view their quote history and duplicate past requests.
- **Age Verification**: Mandatory 21+ gatekeeping upon site entry.

### 3.2. Product Catalog & Discovery
- **Dynamic Product Grid**: Real-time loading of products from the database.
- **Categorization**: Products organized by type (Peptides, Equipment, etc.).
- **Search & Filtering**: Capability to search by name or ID and filter by category.
- **Detailed Product Pages**: 
    - High-resolution imagery.
    - Comprehensive specifications (Purity, Form, Storage, etc.).
    - Research applications and target user descriptions.
    - Gallery view including COA (Certificate of Analysis) previews.

### 3.3. Quote & Inquiry System (B2B Workflow)
- **Quote List (Cart)**: Users add items to a "Quote List" instead of a direct checkout.
- **Wholesale Inquiries**: Integration with WhatsApp for direct communication with the CEO/Sales.
- **Finalize Inquiry**: A multi-step form to collect shipping details, contact preferences (Email/WhatsApp), and research confirmation.
- **Admin Management**: Admins can review, update status (Pending, Paid, Shipped, etc.), and manage internal notes for quotes.

### 3.4. Scientific & Trust Tools
- **Reconstitution Calculator**: Interactive tool for calculating peptide dosages based on vial size, BAC water volume, and desired dose.
- **Rep Verification Tool**: Anti-scam feature allowing users to verify official sales representatives by ID.
- **COA Repository**: Direct access to third-party lab results (Janoshik, MZ Biolabs) within product pages.
- **Reviews System**: Authenticated customer reviews with an administrative approval workflow.

### 3.5. Administration & Content Management
- **Admin Dashboard**: Centralized hub for managing:
    - Products (Add/Edit/Delete, Image & COA uploads).
    - Quotes/Orders.
    - Site Reviews (Approve/Reject).
    - Site Settings (Marquee messages, WhatsApp contact info).
- **Custom Marquee**: Dynamic announcement bar editable from the admin panel.

### 3.6. Internationalization
- **Multilingual Support**: Integrated Google Translate with a custom professional language selector (EN, ES, FR, DE, ZH, etc.).

---

## 4. Technical Stack
- **Frontend**: HTML5, CSS3 (Custom Glassmorphism/Dark Theme), Vanilla JavaScript.
- **Backend**: PHP (REST-like API structure).
- **Database**: MySQL (Relational schema for Users, Products, Cart, Quotes, and Reviews).
- **Styling**: FontAwesome for icons, Inter & Outfit Google Fonts.
- **Integration**: Google Translate API.

---

## 5. User Flow
1. **Entry**: User passes 21+ age verification.
2. **Discovery**: User browses the catalog or uses the Reconstitution Calculator.
3. **Selection**: User views product details and adds items to the "Quote List".
4. **Authentication**: User signs in or registers to finalize the inquiry.
5. **Inquiry**: User submits shipping and contact details.
6. **Fulfillment**: Admin reviews the quote, contacts the user via WhatsApp/Email, and updates the quote status upon payment/shipping.

---

## 6. Data Model (Key Entities)
- **Users**: ID, Name, Email, Password, Role, Timestamps.
- **Products**: ID, Name, Category, Description, Purity, Images, COA Paths, Specs, Applications.
- **Cart Items**: UserID, ProductID, Quantity.
- **Quote Requests**: UserID, Shipping Info, Contact Detail, Status, Internal Notes.
- **Reviews**: Display Name, Rating, Text, Status (Pending/Approved).

---

## 7. Future Enhancements
- **Real-time Inventory Tracking**: Automated stock level alerts.
- **Advanced Analytics**: Admin insights into popular products and geographic demand.
- **Customer Loyalty Program**: Tiered pricing for frequent wholesale buyers.
- **Expanded Scientific Tools**: More calculators (e.g., half-life, molarity).
