

import pymysql
from datetime import datetime
import discord
from discord.ext import commands

# Discord bot token
TOKEN = 'YOUR_DISCORD_BOT_TOKEN'
# Establish database connection
conn = pymysql.connect(
    host='localhost',
    user='root',
    password='qwerty1234',
    database='sea_auth'
)

# Define the bot with intents
intents = discord.Intents.default()
intents.message_content = True
bot = commands.Bot(command_prefix="!", intents=intents)

async def on_ready(self):
        if not self.synced:
            await bot.tree.sync()  # Sync slash commands
            self.synced = True
        print(f'Logged in as {self.user}')

# Slash command for posting
@bot.tree.command(name="post")
async def post(interaction: discord.Interaction, title: str, content: str, description: str):
    # Acknowledge the interaction immediately
    await interaction.response.send_message("Processing your post...", ephemeral=True)
    
    try:
        # Open a cursor
        with conn.cursor() as cursor:
            # SQL query to insert post data into 'blog_posts' table
            sql = "INSERT INTO blog_posts (title, content, description, created_at) VALUES (%s, %s, %s, %s)"
            
            # Execute the query
            cursor.execute(sql, (title.strip(), content.strip(), description.strip(), datetime.utcnow()))
            
            # Commit the transaction
            conn.commit()
        
        # Follow-up with the success message
        await interaction.followup.send("Post successfully created.")
    
    except pymysql.ProgrammingError as e:
        # Handle specific SQL errors
        await interaction.followup.send(f"Database error: {str(e)}")
    
    except Exception as e:
        # Handle general errors
        await interaction.followup.send(f"Unexpected error: {str(e)}")


# Run the bot
bot.run(TOKEN)
