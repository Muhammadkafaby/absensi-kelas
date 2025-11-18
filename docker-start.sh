#!/bin/bash
# Quick Start Script for Docker Setup

echo "🐳 Absensi Kelas - Docker Quick Start"
echo "======================================"
echo ""

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed!"
    echo "Please install Docker from: https://www.docker.com/get-started"
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed!"
    echo "Please install Docker Compose"
    exit 1
fi

echo "✅ Docker and Docker Compose are installed"
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file from template..."
    cp .env.docker .env
    echo "✅ .env file created"
    echo "⚠️  Please edit .env file if you need to change database credentials"
    echo ""
else
    echo "✅ .env file already exists"
    echo ""
fi

# Ask to build
echo "🔨 Building Docker containers..."
echo "This may take a few minutes on first run..."
echo ""

docker-compose up -d --build

# Wait for containers to be healthy
echo ""
echo "⏳ Waiting for containers to be ready..."
sleep 5

# Check container status
echo ""
echo "📊 Container Status:"
docker-compose ps

echo ""
echo "✨ Setup Complete!"
echo ""
echo "🌐 Access the application:"
echo "   - Web App:      http://localhost:8080"
echo "   - PhpMyAdmin:   http://localhost:8081"
echo ""
echo "📚 Next Steps:"
echo "   1. Import your database (see DOCKER.md for instructions)"
echo "   2. Access the application in your browser"
echo ""
echo "📖 For detailed documentation, see DOCKER.md"
echo ""
echo "🛠️  Useful commands:"
echo "   - View logs:     docker-compose logs -f"
echo "   - Stop:          docker-compose down"
echo "   - Restart:       docker-compose restart"
echo ""
