# Google Maps API Setup

Geo Posts requires a Google Maps API key to render maps. This guide walks you through creating one.

## Step 1: Create a Google Cloud Project

1. Go to the [Google Cloud Console](https://console.cloud.google.com/)
2. Click **Select a project** at the top, then **New Project**
3. Give it a name (e.g., "My Website Maps") and click **Create**
4. Make sure the new project is selected

## Step 2: Enable the Required APIs

1. Go to **APIs & Services > Library**
2. Search for and enable each of these:
   - **Maps JavaScript API**
   - **Places API (New)**

## Step 3: Create an API Key

1. Go to **APIs & Services > Credentials**
2. Click **Create Credentials > API Key**
3. Copy the key that appears

## Step 4: Restrict the Key (Recommended)

To prevent misuse of your API key:

1. Click on the API key you just created
2. Under **Application restrictions**, select **HTTP referrers (websites)**
3. Add your website URLs:
   - `https://yoursite.com/*`
   - `https://www.yoursite.com/*`
4. Under **API restrictions**, select **Restrict key** and choose:
   - Maps JavaScript API
   - Places API (New)
5. Click **Save**

## Step 5: Add the Key to Geo Posts

1. In WordPress, go to **Settings > Geo Posts**
2. Paste your API key into the **Your Google API key** field
3. Click **Save Changes**

## Billing

Google Maps Platform requires a billing account, but provides a free monthly credit of $200. For most small-to-medium sites, this is more than sufficient.

See [Google Maps Platform pricing](https://developers.google.com/maps/billing-and-pricing/pricing) for details.

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Map shows "For development purposes only" watermark | Enable billing on your Google Cloud project |
| Map doesn't load at all | Check the browser console (F12) for error messages. Common causes: invalid API key, APIs not enabled, or referrer restrictions blocking the request |
| "RefererNotAllowedMapError" | Add your site URL to the API key's HTTP referrer restrictions |
| "ApiNotActivatedMapError" | Enable the Maps JavaScript API in the Google Cloud Console |
